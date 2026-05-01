--  SCHEMA: Shopping Cart & Order System
--  Dữ liệu lấy từ: mowgarden.com


-- CREATE DATABASE
CREATE DATABASE IF NOT EXISTS quanlybancaycanh 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_general_ci;

USE quanlybancaycanh;

-- Users
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(20) DEFAULT 'customer',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Categories
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL
);

-- Products
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NULL,
    name VARCHAR(200) NOT NULL,
    description TEXT,
    price DECIMAL(12,2) NOT NULL DEFAULT 0,
    stock INT NOT NULL DEFAULT 0,
    image VARCHAR(300) DEFAULT 'default.jpg',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (category_id) 
        REFERENCES categories(id) 
        ON DELETE SET NULL
);

-- Cart Items
CREATE TABLE cart_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    added_at DATETIME DEFAULT CURRENT_TIMESTAMP,

    UNIQUE (user_id, product_id),

    FOREIGN KEY (user_id) 
        REFERENCES users(id) 
        ON DELETE CASCADE,

    FOREIGN KEY (product_id) 
        REFERENCES products(id) 
        ON DELETE CASCADE
);

-- Orders
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    subtotal DECIMAL(12,2) NOT NULL DEFAULT 0,
    vat_amount DECIMAL(12,2) NOT NULL DEFAULT 0,
    shipping_fee DECIMAL(12,2) NOT NULL DEFAULT 0,
    total DECIMAL(12,2) NOT NULL DEFAULT 0,
    pricing_strategy VARCHAR(50) DEFAULT 'standard',
    status VARCHAR(20) DEFAULT 'pending',
    shipping_address TEXT,
    note TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id) 
        REFERENCES users(id)
);

-- Order Items
CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    name VARCHAR(200) NOT NULL,
    price DECIMAL(12,2) NOT NULL,
    quantity INT NOT NULL,
    subtotal DECIMAL(12,2) NOT NULL,

    FOREIGN KEY (order_id) 
        REFERENCES orders(id) 
        ON DELETE CASCADE,

    FOREIGN KEY (product_id) 
        REFERENCES products(id)
);

-- Password Resets
CREATE TABLE password_resets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(150) NOT NULL,
    token VARCHAR(100) NOT NULL,
    het_han DATETIME NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Categories data
INSERT INTO categories (name) VALUES
('Cây trong nhà'),
('Cây ngoài trời'),
('Chậu gốm sứ'),
('Chậu xi măng đá mài'),
('Phụ kiện & Dụng cụ');



-- Users mẫu
INSERT INTO users (name, email, password, role) VALUES
('Admin System', 'admin@shop.com', '123456', 'admi'),
('Nguyễn Văn A', 'user@shop.com', '123456', 'customer'),
('Trần Thị Bình', 'binh@shop.com', '123456', 'customer'),
('Lê Minh Châu', 'chau@shop.com', '123456', 'customer'),
('Phạm Thị Dung', 'dung@shop.com', '123456', 'customer');




-- Products
-- Tên file ảnh đặt theo tên sản phẩm, lưu tại:
-- public/assets/images/products/<ten-file.jpg>
-- Tải ảnh về từ mowgarden.com rồi đổi tên theo cột image bên dưới

INSERT INTO products (category_id, name, description, price, stock, image) VALUES
(1, 'Cây tùng bồng lai tiểu cảnh chậu sứ thổ cẩm TUBO005',
 'Cây tùng bồng lai trồng trong chậu sứ thổ cẩm, dáng tiểu cảnh đẹp, phù hợp để bàn làm việc hoặc kệ trang trí.',
 500000, 30, 'tung-bong-lai-tieu-canh-chau-su-tho-cam.jpg'),

(1, 'Cây phát tài bộ 5 thiết mộc lan CPTK001',
 'Bộ 5 cây phát tài thiết mộc lan, mang ý nghĩa phong thủy tốt lành, thích hợp làm quà tặng hoặc trang trí góc nhà.',
 750000, 25, 'phat-tai-bo-5-thiet-moc-lan.jpg'),

(1, 'Cây kim ngân ba thân chậu sứ gấu BearBrick LONI040',
    'Cây kim ngân 3 thân trồng trong chậu sứ hình gấu BearBrick độc đáo, mang lại may mắn và tài lộc.',
    280000, 50, 'kim-ngan-ba-than-chau-su-gau-bearbrick.jpg'),

(1, 'Cây trầu bà đế vương xanh Imperial Green chậu sứ PHIG006',
    'Trầu bà đế vương xanh giống Imperial Green, lá to bóng đẹp, trồng trong chậu sứ mặt cười dễ thương.',
    120000, 100, 'trau-ba-de-vuong-xanh-imperial-green-chau-su.jpg'),

(1, 'Cây lưỡi hổ vương miện chậu sứ để bàn SANS002',
    'Lưỡi hổ dạng vương miện, thân trụ tròn độc đáo, lọc không khí tốt, ít cần tưới nước, dễ chăm sóc.',
    120000, 80, 'luoi-ho-vuong-mien-chau-su-de-ban.jpg'),

(1, 'Cây cỏ lan chi để bàn chậu sứ mặt cười SPI004',
    'Cây cỏ lan chi nhỏ xinh, tán lá rủ tự nhiên, trồng trong chậu sứ mặt cười ngộ nghĩnh.',
    120000, 120, 'co-lan-chi-de-ban-chau-su-mat-cuoi.jpg'),

(1, 'Cây trầu bà đế vương đỏ Red Rojo chậu sứ PHIR008',
    'Trầu bà đế vương đỏ giống Red Rojo, lá đỏ đậm nổi bật, điểm nhấn màu sắc cho không gian sống.',
    320000, 40, 'trau-ba-de-vuong-do-red-rojo-chau-su.jpg'),

(1, 'Cây lưỡi hổ Bantel Sensation chậu ươm STBS001',
    'Lưỡi hổ Bantel Sensation có sọc trắng kem đặc trưng, dễ trồng, chịu bóng tốt.',
    160000, 60, 'luoi-ho-bantel-sensation-chau-uom.jpg'),

(1, 'Cây hạnh phúc để sàn 2 tầng lớn chậu đá mài RADE033',
    'Cây hạnh phúc 2 tầng kích thước lớn, dáng đẹp uy nghi, phù hợp đặt sảnh hoặc văn phòng.',
    1200000, 15, 'hanh-phuc-de-san-2-tang-lon-chau-da-mai.jpg'),

(1, 'Cây trầu bà cột chậu xi măng trụ vuông CTBC007',
    'Trầu bà cột leo giá thể, kết hợp chậu xi măng trụ vuông vân sọc ngang hiện đại.',
    1100000, 20, 'trau-ba-cot-chau-xi-mang-tru-vuong.jpg'),

(1, 'Cây hồng môn cỡ nhỏ chậu sứ trắng ANTH010',
    'Hồng môn hoa đỏ tươi, lá bóng xanh đậm, cây nhỏ gọn phù hợp để bàn.',
    240000, 45, 'hong-mon-co-nho-chau-su-trang.jpg'),

(1, 'Cây ngũ gia bì cẩm thạch nhỏ chậu ươm SCHE020',
    'Ngũ gia bì cẩm thạch lá xanh viền trắng đặc trưng, dáng cây bụi nhỏ gọn, dễ chăm sóc.',
    100000, 90, 'ngu-gia-bi-cam-thach-nho-chau-uom.jpg'),

(1, 'Cây kim ngân một thân để bàn chậu sứ LONI039',
    'Kim ngân một thân thắt bính duyên dáng, biểu tượng tài lộc, trồng trong chậu sứ trắng tinh tế.',
    450000, 35, 'kim-ngan-mot-than-de-ban-chau-su.jpg'),

(1, 'Cây cọ lá xẻ mini để bàn chậu sứ hoa văn LIVI005',
    'Cọ lá xẻ mini dáng nhiệt đới, lá xòe tự nhiên, trồng trong chậu sứ hoa văn cổ điển đẹp mắt.',
    160000, 55, 'co-la-xe-mini-de-ban-chau-su-hoa-van.jpg'),

(1, 'Cây hạnh phúc để sàn 2 tầng chậu sứ hoa văn RADE032',
    'Hạnh phúc 2 tầng thanh lịch trong chậu sứ hoa văn, phù hợp văn phòng, phòng khách sang trọng.',
    900000, 18, 'hanh-phuc-de-san-2-tang-chau-su-hoa-van.jpg'),

(1, 'Cây hạnh phúc để sàn chậu sứ RADE031',
    'Cây hạnh phúc một tầng để sàn, dáng thanh mảnh, chậu sứ trắng tối giản.',
    550000, 22, 'hanh-phuc-de-san-chau-su-trang.jpg'),

(1, 'Cây kim ngân thắt bính tiểu cảnh chậu sứ LONI038',
    'Kim ngân thắt bính tiểu cảnh đặc sắc, kết hợp đá và rêu tự nhiên, chậu sứ cao cấp.',
    380000, 28, 'kim-ngan-that-binh-tieu-canh-chau-su.jpg'),

(1, 'Cây lưỡi hổ xanh mini Black Gold chậu sứ SHBG005',
    'Lưỡi hổ xanh viền vàng Black Gold, kích thước mini dễ thương, không tốn nhiều không gian.',
    120000, 100, 'luoi-ho-xanh-mini-black-gold-chau-su.jpg'),

(1, 'Cây lan ý cỡ lớn để bàn chậu sứ trắng PEAC005',
    'Lan ý hoa trắng tinh khiết, lọc không khí hiệu quả, cỡ lớn phù hợp đặt góc phòng hoặc sảnh.',
    240000, 40, 'lan-y-co-lon-de-ban-chau-su-trang.jpg'),

(1, 'Cây phú quý chậu sứ thổ cẩm để bàn AGLA104',
    'Phú quý lá to bóng xanh, tên gọi may mắn, chậu sứ thổ cẩm hoạ tiết dân tộc độc đáo.',
    320000, 35, 'phu-quy-chau-su-tho-cam-de-ban.jpg'),

(1, 'Cây ngọc ngân để bàn chậu sứ AGSN010',
    'Ngọc ngân lá xanh bóng với viền kem đặc trưng, dáng bụi gọn, dễ sống trong nhà ít ánh sáng.',
    160000, 75, 'ngoc-ngan-de-ban-chau-su.jpg'),

(1, 'Cây kim ngân 3 thân để bàn chậu marle LONI037',
    'Kim ngân 3 thân nhỏ nhắn, chậu marle hiện đại, quà tặng ý nghĩa cho bạn bè, đồng nghiệp.',
    140000, 60, 'kim-ngan-3-than-de-ban-chau-marle.jpg'),

(1, 'Cây bàng Đài Loan cẩm thạch chậu sứ BUBU007',
    'Bàng Đài Loan lá cẩm thạch xanh trắng độc đáo, dáng cây cao uy nghi, phù hợp sảnh lớn.',
    1200000, 12, 'bang-dai-loan-cam-thach-chau-su.jpg'),

(1, 'Cây hạnh phúc một thân cổ thụ để bàn chậu sứ RADE030',
    'Hạnh phúc một thân dáng cổ thụ, thân gỗ tự nhiên, trồng trong chậu sứ sang trọng.',
    350000, 20, 'hanh-phuc-mot-than-co-thu-de-ban-chau-su.jpg'),

(1, 'Cây phát tài núi 2 tầng chậu đá mài đen CPTN013',
    'Phát tài núi 2 tầng hùng vĩ, chậu đá mài đen sang trọng, biểu tượng thịnh vượng.',
    1750000, 10, 'phat-tai-nui-2-tang-chau-da-mai-den.jpg'),

(1, 'Cây hạnh phúc một thân cao 1m6 chậu đất nung RADE024',
    'Hạnh phúc một thân cao 1m6, dáng thẳng thanh lịch, chậu đất nung tự nhiên mộc mạc.',
    900000, 15, 'hanh-phuc-mot-than-cao-1m6-chau-dat-nung.jpg'),

(1, 'Cây ngũ gia bì để sàn chậu trụ tròn đá mài SCHE017',
    'Ngũ gia bì cỡ lớn để sàn, chậu trụ tròn đá mài cao cấp, lọc không khí tốt cho văn phòng.',
    750000, 18, 'ngu-gia-bi-de-san-chau-tru-tron-da-mai.jpg'),

(1, 'Cây trúc bách hợp một thân chậu xi măng CTBH008',
    'Trúc bách hợp thân thẳng thanh lịch, chậu xi măng giọt nước hiện đại.',
    950000, 12, 'truc-bach-hop-mot-than-chau-xi-mang.jpg'),

(1, 'Cây trầu bà thanh xuân cỡ lớn chậu đá mài TBTX008',
    'Trầu bà thanh xuân lá dài thanh thoát, cỡ lớn đặt sàn, chậu đá mài xám sang trọng.',
    1200000, 8, 'trau-ba-thanh-xuan-co-lon-chau-da-mai.jpg'),

(1, 'Cây trầu bà Nam Mỹ Monstera Borsigiana chậu sứ MONS106',
    'Monstera Borsigiana lá xẻ đặc trưng, kích thước vừa phải, dễ chăm sóc trong nhà.',
    750000, 25, 'trau-ba-nam-my-monstera-borsigiana-chau-su.jpg'),

(1, 'Cây cau vàng Nhật Bản cỡ trung chậu đá mài CHRY009',
    'Cau vàng Nhật Bản lá vàng óng rủ mềm mại, cỡ trung, chậu đá mài đen tinh tế.',
    740000, 20, 'cau-vang-nhat-ban-co-trung-chau-da-mai.jpg'),

(1, 'Cây hạnh phúc 2 tầng chậu sứ trắng RADE009',
    'Hạnh phúc 2 tầng cân đối hài hoà, chậu sứ trắng tối giản, biểu tượng hạnh phúc và thịnh vượng.',
    990000, 15, 'hanh-phuc-2-tang-chau-su-trang.jpg'),

(1, 'Cây bàng Singapore cao 2m dáng tree chậu sứ LYRA023',
    'Bàng Singapore cao 2m dáng cây thật, tán lá rộng xanh mướt, điểm nhấn nổi bật cho sảnh lớn.',
    2200000, 5, 'bang-singapore-cao-2m-dang-tree-chau-su.jpg'),

(1, 'Cây kim ngân thắt bính kích thước lớn LONI017',
    'Kim ngân thắt bính cỡ lớn để sàn, biểu tượng phong thủy tài lộc mạnh mẽ.',
    1200000, 10, 'kim-ngan-that-binh-kich-thuoc-lon.jpg'),

(1, 'Cây phát tài núi 2 tầng chậu sứ trắng CPTN012',
    'Phát tài núi 2 tầng trong chậu sứ trắng thanh nhã, phù hợp trang trí văn phòng và tặng khai trương.',
    1750000, 12, 'phat-tai-nui-2-tang-chau-su-trang.jpg'),

(1, 'Chậu cây mix đại phú gia trầu bà Neon NTMX021',
    'Combo chậu cây mix đại phú gia và trầu bà Neon, bộ đôi hoàn hảo trang trí góc sống hay văn phòng.',
    820000, 20, 'mix-dai-phu-gia-trau-ba-neon.jpg'),

(1, 'Chậu cây mix đại phú gia đô la may mắn NTMX011',
    'Bộ 3 cây phong thủy đại phú gia, đô la, may mắn trong một chậu, mang lại vượng khí.',
    1400000, 10, 'mix-dai-phu-gia-do-la-may-man.jpg'),

-- CHẬU GỐM SỨ
(3, 'Chậu gốm sứ hình trụ họa tiết Geometric GOSU059',
    'Chậu sứ hình trụ với họa tiết geometric hiện đại, phù hợp phong cách tối giản, nhiều kích thước.',
    130000, 150, 'chau-gom-su-tru-hoa-tiet-geometric.jpg'),

(3, 'Chậu gốm sứ họa tiết lá Monstera có dĩa GOSU057',
    'Chậu sứ trắng in họa tiết lá Monstera xanh lá, kèm đĩa lót, nhỏ gọn dễ thương.',
    40000, 200, 'chau-gom-su-hoa-tiet-la-monstera-co-dia.jpg'),

(3, 'Chậu gốm sứ họa tiết hoa màu trắng có dĩa GOSU056',
    'Chậu sứ trắng họa tiết hoa nổi bật, có kèm đĩa lót tiện lợi, phù hợp cây để bàn mini.',
    40000, 200, 'chau-gom-su-hoa-tiet-hoa-trang-co-dia.jpg'),

(3, 'Chậu gốm sứ viền hoa cúc có dĩa màu trắng GOSU055',
    'Chậu sứ viền hoa cúc tinh tế, có đĩa lót, kiểu dáng cổ điển thanh lịch.',
    40000, 180, 'chau-gom-su-vien-hoa-cuc-co-dia-trang.jpg'),

(3, 'Chậu gốm sứ hình trụ trứng màu trắng GOSU054',
    'Chậu sứ hình trụ trứng bo tròn mềm mại, màu trắng tinh, phù hợp mọi loại cây nhỏ và vừa.',
    130000, 120, 'chau-gom-su-tru-trung-mau-trang.jpg'),

(3, 'Chậu gốm sứ hình khối vân gợn sóng màu trắng GOSU053',
    'Chậu sứ hình khối vuông với vân gợn sóng nổi, hiệu ứng 3D đẹp mắt, phong cách Nhật Bản.',
    115000, 100, 'chau-gom-su-hinh-khoi-van-gon-song-trang.jpg'),

(3, 'Chậu gốm sứ trụ tròn họa tiết kẻ sọc màu trắng GOSU052',
    'Chậu sứ trụ tròn với họa tiết kẻ sọc tinh tế, phong cách Scandinavia, nhiều kích thước.',
    165000, 130, 'chau-gom-su-tru-tron-ke-soc-trang.jpg'),

(3, 'Chậu gốm sứ họa tiết tam giác màu trắng GOSU051',
    'Chậu sứ trắng với họa tiết tam giác hình học, phong cách hiện đại tối giản.',
    165000, 110, 'chau-gom-su-hoa-tiet-tam-giac-trang.jpg'),

(3, 'Chậu gốm sứ họa tiết ô vuông màu trắng GOSU050',
    'Chậu sứ trắng với họa tiết ô vuông đều đặn, kiểu dáng geometric tối giản đẹp mắt.',
    165000, 100, 'chau-gom-su-hoa-tiet-o-vuong-trang.jpg'),

(3, 'Chậu gốm sứ họa tiết đan mây màu đen GOSU049',
    'Chậu sứ đen với họa tiết đan mây nổi, sang trọng và cá tính, điểm nhấn cho không gian.',
    205000, 80, 'chau-gom-su-hoa-tiet-dan-may-den.jpg'),

-- CHẬU XI MĂNG
(4, 'Chậu đá mài Granito dáng trụ vót màu trắng XMDM018',
    'Chậu xi măng đá mài Granito dáng trụ vót thanh mảnh, màu trắng sáng, nhiều kích thước lựa chọn.',
    380000, 60, 'chau-da-mai-granito-tru-vot-trang.jpg'),

(4, 'Chậu xi măng hình trụ vuông vân sọc ngang màu đen XMDM017',
    'Chậu xi măng trụ vuông vân sọc ngang màu đen cá tính, phù hợp phong cách industrial hiện đại.',
    500000, 40, 'chau-xi-mang-tru-vuong-van-soc-ngang-den.jpg'),

(4, 'Chậu đá mài Granito cao cấp dáng Remy màu trắng XMDM015',
    'Chậu xi măng đá mài Granito dáng Remy bo tròn mềm mại, cao cấp sang trọng, nhiều cỡ.',
    460000, 50, 'chau-da-mai-granito-dang-remy-trang.jpg'),

(4, 'Chậu xi măng nhẹ hình trụ vát đáy vân quấn rối XMDM014',
    'Chậu xi măng nhẹ với vân quấn rối độc đáo, dáng trụ vát đáy tạo chiều sâu thị giác.',
    285000, 70, 'chau-xi-mang-nhe-tru-vat-day-van-quan-roi.jpg'),

(4, 'Chậu xi măng đá mài trụ tròn vẽ zigzac XMDM013',
    'Chậu xi măng trụ tròn với họa tiết zigzac vẽ tay độc đáo, cá tính, phong cách nghệ thuật.',
    320000, 45, 'chau-xi-mang-da-mai-tru-tron-ve-zigzac.jpg'),

(4, 'Chậu xi măng đá mài trụ tròn dáng thấp XMDM012',
    'Chậu xi măng đá mài trụ tròn dáng thấp, chắc chắn bền bỉ, giá tốt, nhiều kích thước.',
    115000, 120, 'chau-xi-mang-da-mai-tru-tron-dang-thap.jpg'),

(4, 'Chậu xi măng hình giọt nước sơn họa tiết XMDM011',
    'Chậu xi măng hình giọt nước sơn họa tiết trang trí, kích thước lớn 32x52cm.',
    440000, 30, 'chau-xi-mang-giot-nuoc-son-hoa-tiet.jpg'),

(4, 'Chậu xi măng hình giọt nước sơn nhiều màu XMDM010',
    'Chậu xi măng hình giọt nước với màu sắc tươi tắn phong phú, điểm nhấn màu sắc cho không gian.',
    480000, 25, 'chau-xi-mang-giot-nuoc-son-nhieu-mau.jpg'),

(4, 'Chậu xi măng hình giọt nước tự nhiên 32x52cm XMDM009',
    'Chậu xi măng hình giọt nước kích thước lớn, màu tự nhiên, phù hợp cây lớn ngoài sân.',
    340000, 35, 'chau-xi-mang-giot-nuoc-tu-nhien.jpg'),

(4, 'Chậu xi măng hình trụ sơn họa tiết 40x40cm XMDM008',
    'Chậu xi măng trụ tròn cỡ lớn 40x40cm, sơn họa tiết trang trí đẹp mắt, bền với thời tiết.',
    520000, 20, 'chau-xi-mang-tru-tron-son-hoa-tiet-40cm.jpg');

