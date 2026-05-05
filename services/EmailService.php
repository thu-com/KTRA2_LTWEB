<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class EmailService
{
    private string $gmailUser = 'linhchi04052004@gmail.com';     // <-- Gmail của bạn
    private string $gmailPass = 'wdjv genp yeok ptif';   // <-- App Password (16 ký tự)

    public function sendOrderConfirmation(array $user, array $order): bool
    {
        $subject = "[OOP Shop] Xác nhận đơn hàng #{$order['id']}";
        $body    = $this->buildOrderConfirmationHtml($user, $order);
        return $this->send($user['email'], $user['name'], $subject, $body);
    }

    public function sendShippingNotification(array $user, array $order): bool
    {
        $subject = "[OOP Shop] Đơn hàng #{$order['id']} đang được giao";
        $body    = "<p>Kính gửi <strong>{$user['name']}</strong>,</p>
                    <p>Đơn hàng <strong>#{$order['id']}</strong> đang được giao.</p>
                    <p>Trân trọng,<br><strong>OOP Shop</strong></p>";
        return $this->send($user['email'], $user['name'], $subject, $body);
    }

    private function send(string $toEmail, string $toName, string $subject, string $body): bool
    {
        // Luôn ghi log file
        $logEntry = sprintf(
            "[%s] TO: %s <%s>\nSUBJECT: %s\nBODY:\n%s\n%s\n",
            date('Y-m-d H:i:s'), $toName, $toEmail,
            $subject, strip_tags($body), str_repeat('-', 70)
        );
        file_put_contents(LOG_PATH . '/emails.log', $logEntry, FILE_APPEND | LOCK_EX);

        // Gửi email thật qua Gmail SMTP
        try {
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = $this->gmailUser;
            $mail->Password   = $this->gmailPass;
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;
            $mail->CharSet    = 'UTF-8';

            $mail->setFrom($this->gmailUser, 'OOP Shop');
            $mail->addAddress($toEmail, $toName);
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $body;
            $mail->send();
            return true;
        } catch (Exception $e) {
            // Nếu gửi lỗi thì ghi thêm vào log, không crash app
            file_put_contents(
                LOG_PATH . '/emails.log',
                "[ERROR] " . $e->getMessage() . "\n",
                FILE_APPEND
            );
            return false;
        }
    }

    private function buildOrderConfirmationHtml(array $user, array $order): string
    {
        $itemsHtml = '';
        foreach ($order['items'] as $item) {
            $sub = number_format($item['subtotal'], 0, ',', '.');
            $prc = number_format($item['price'],    0, ',', '.');
            $itemsHtml .= "<tr>
                <td style='padding:8px;border:1px solid #ddd'>{$item['name']}</td>
                <td style='padding:8px;border:1px solid #ddd;text-align:right'>{$prc}đ</td>
                <td style='padding:8px;border:1px solid #ddd;text-align:center'>{$item['quantity']}</td>
                <td style='padding:8px;border:1px solid #ddd;text-align:right'>{$sub}đ</td>
            </tr>";
        }
        $sub  = number_format($order['subtotal'],     0, ',', '.');
        $vat  = number_format($order['vat_amount'],   0, ',', '.');
        $ship = number_format($order['shipping_fee'], 0, ',', '.');
        $tot  = number_format($order['total'],         0, ',', '.');

        return "<!DOCTYPE html><html><body style='font-family:Arial,sans-serif'>
        <div style='max-width:600px;margin:auto;background:#fff;padding:20px;border:1px solid #eee'>
            <h2 style='color:#e67e22'>🛒 OOP Shop - Xác nhận đơn hàng</h2>
            <p>Kính gửi <strong>{$user['name']}</strong>,</p>
            <p>Đơn hàng <strong>#{$order['id']}</strong> đã đặt thành công!</p>
            <table style='width:100%;border-collapse:collapse'>
                <tr style='background:#f8f9fa'>
                    <th style='padding:8px;border:1px solid #ddd;text-align:left'>Sản phẩm</th>
                    <th style='padding:8px;border:1px solid #ddd'>Đơn giá</th>
                    <th style='padding:8px;border:1px solid #ddd'>SL</th>
                    <th style='padding:8px;border:1px solid #ddd'>Thành tiền</th>
                </tr>
                {$itemsHtml}
            </table>
            <table style='width:100%;margin-top:10px'>
                <tr><td>Tạm tính:</td><td style='text-align:right'>{$sub}đ</td></tr>
                <tr><td>VAT:</td><td style='text-align:right'>{$vat}đ</td></tr>
                <tr><td>Phí vận chuyển:</td><td style='text-align:right'>{$ship}đ</td></tr>
                <tr style='font-weight:bold;font-size:1.1em;color:#e67e22'>
                    <td>TỔNG CỘNG:</td><td style='text-align:right'>{$tot}đ</td>
                </tr>
            </table>
            <p style='margin-top:20px'>Địa chỉ: <em>{$order['shipping_address']}</em></p>
            <p>Trân trọng,<br><strong>OOP Shop Team</strong></p>
        </div></body></html>";
    }
}