# Bảng users

INSERT INTO users (name, email, password, phone, address, status) VALUES
('Nguyễn Văn An', 'nguyenvanan@example.com', '123456', '0912345678', '123 Đường Nguyễn Trãi, Quận 1, TP.HCM', 'Active'),
('Trần Thị Bình', 'tranthibinh@example.com', '123456', '0923456789', '456 Đường Lê Lợi, Quận 2, TP.HCM', 'Inactive'),
('Lê Minh Châu', 'leminhchau@example.com', '123456', '0934567890', '789 Đường Trần Hưng Đạo, Quận 3, TP.HCM', 'Active'),
('Phan Thị Dung', 'phanthidung@example.com', '123456', '0945678901', '101 Đường Võ Thị Sáu, Quận 4, TP.HCM', 'Active'),
('Vũ Hoàng Duy', 'vuhoangduy@example.com', '123456', '0956789012', '202 Đường Nguyễn Văn Cừ, Quận 5, TP.HCM', 'Inactive'),
('Nguyễn Thị Hương', 'nguyenhuong@example.com', '123456', '0967890123', '303 Đường Cách Mạng Tháng 8, Quận 6, TP.HCM', 'Active'),
('Đỗ Minh Hoàng', 'dominhoang@example.com', '123456', '0978901234', '404 Đường Phan Đăng Lưu, Quận 7, TP.HCM', 'Active'),
('Bùi Minh Hiếu', 'buiminhieu@example.com', '123456', '0989012345', '505 Đường Nguyễn Thị Minh Khai, Quận 8, TP.HCM', 'Inactive'),
('Ngô Thị Lan', 'ngothilan@example.com', '123456', '0990123456', '606 Đường Lý Thường Kiệt, Quận 9, TP.HCM', 'Active'),
('Lý Hoàng Hưng', 'lyhoanghung@example.com', '123456', '0901234567', '707 Đường Trường Sa, Quận 10, TP.HCM', 'Active'),
('Hoàng Minh Khoa', 'hoangminkhoa@example.com', '123456', '0912345678', '808 Đường Lạc Long Quân, Quận 11, TP.HCM', 'Inactive'),
('Cao Thị Lan', 'caothilan@example.com', '123456', '0923456789', '909 Đường Nguyễn Huệ, Quận 12, TP.HCM', 'Active'),
('Lâm Minh Mạnh', 'lamminhmanh@example.com', '123456', '0934567890', '111 Đường Đinh Tiên Hoàng, Quận 13, TP.HCM', 'Active'),
('Trương Thị Nhi', 'truongthihi@example.com', '123456', '0945678901', '222 Đường Võ Văn Tần, Quận 14, TP.HCM', 'Inactive'),
('Phạm Hoàng Quân', 'phamhoangquan@example.com', '123456', '0956789012', '333 Đường Phạm Ngọc Thạch, Quận 15, TP.HCM', 'Active');

# Bảng cate
INSERT INTO categories (name, slug, status) VALUES
('Nồi cơm điện', 'noi-com-dien', 'Active'),
('Tủ lạnh', 'tu-lanh', 'Active'),
('Máy giặt', 'may-giat', 'Active'),
('Lò vi sóng', 'lo-vi-song', 'Active'),
('Quạt điện', 'quat-dien', 'Active'),
('Máy lọc nước', 'may-loc-nuoc', 'Active'),
('Máy pha cà phê', 'may-pha-ca-phe', 'Active');

# Bảng brands
INSERT INTO brands (name, slug, status) VALUES
('Panasonic', 'panasonic', 'Active'),
('Samsung', 'samsung', 'Active'),
('Sony', 'sony', 'Active'),
('Electrolux', 'electrolux', 'Active'),
('Toshiba', 'toshiba', 'Active'),
('Delonghi', 'delonghi', 'Active'),
('Nespresso', 'nespresso', 'Active'),
('Lock&Lock', 'locknlock', 'Active'),
('Tiross', 'tiross', 'Active'),
('Karofi', 'karofi', 'Active'),
('Kangaroo', 'kangaroo', 'Active'),
('Sunhouse', 'sunhouse', 'Active'),
('AO Smith', 'ao-smith', 'Active'),
('Xiaomi', 'xiaomi', 'Active'),
('Philips', 'philips', 'Active');


# Bảng product
INSERT INTO products (name, slug, description, summary, stock, price, discounted_price, images, category_id, brand_id, status) VALUES
('Nồi cơm điện Panasonic 1L', 'noi-com-dien-panasonic-1l', 'Nồi cơm điện Panasonic 1L, tiết kiệm điện, dễ sử dụng.', 'Nồi cơm điện tiện dụng', 0, 850000, 650000, '../Uploads/img1.jpg', 1, 1, 'Active'),
('Tủ lạnh Samsung 300L', 'tu-lanh-samsung-300l', 'Tủ lạnh Samsung 300L, tiết kiệm điện, bảo quản thực phẩm lâu dài.', 'Tủ lạnh tiết kiệm năng lượng', 30, 7500000, 7000000, '../Uploads/img2.jpg', 2, 2, 'Active'),
('Máy giặt Electrolux 8kg', 'may-giat-electrolux-8kg', 'Máy giặt Electrolux 8kg, giặt sạch nhanh, tiết kiệm nước.', 'Máy giặt hiện đại', 40, 5000000, 4500000, '../Uploads/img3.jpg', 3, 4, 'Active'),
('Lò vi sóng Toshiba 20L', 'lo-vi-song-toshiba-20l', 'Lò vi sóng Toshiba 20L, dễ dàng hâm nóng và chế biến thức ăn.', 'Lò vi sóng tiện lợi', 60, 1200000, 1100000, '../Uploads/img4.jpg', 4, 5, 'Active'),
('Quạt điện Panasonic 16 inch', 'quat-dien-panasonic-16inch', 'Quạt điện Panasonic 16 inch, làm mát nhanh và tiết kiệm điện.', 'Quạt điện tiết kiệm năng lượng', 80, 600000, 550000, '../Uploads/img5.jpg', 5, 1, 'Active'),
('Nồi cơm điện Toshiba 1.8L', 'noi-com-dien-toshiba-18l', 'Nồi cơm điện Toshiba 1.8L, nấu cơm nhanh, giữ ấm lâu.', 'Nồi cơm điện tiện ích', 45, 1050000, 950000, '../Uploads/img6.jpg', 1, 5, 'Active'),
('Tủ lạnh Electrolux 450L', 'tu-lanh-electrolux-450l', 'Tủ lạnh Electrolux 450L, bảo quản thực phẩm hiệu quả, làm lạnh nhanh.', 'Tủ lạnh cao cấp', 25, 12000000, 11000000, '../Uploads/img7.jpg', 2, 4, 'Active'),
('Máy giặt Samsung 7kg', 'may-giat-samsung-7kg', 'Máy giặt Samsung 7kg, giặt sạch hiệu quả và tiết kiệm năng lượng.', 'Máy giặt tiết kiệm điện', 50, 4000000, 3800000, '../Uploads/img8.jpg', 3, 2, 'Active'),
('Lò vi sóng Samsung 25L', 'lo-vi-song-samsung-25l', 'Lò vi sóng Samsung 25L, chức năng nấu tự động, thiết kế sang trọng.', 'Lò vi sóng thông minh', 35, 1500000, 1400000, '../Uploads/img9.jpg', 4, 2, 'Active'),
('Quạt điện Sony 18 inch', 'quat-dien-sony-18inch', 'Quạt điện Sony 18 inch, tạo gió mát dễ chịu, chạy êm ái.', 'Quạt điện êm ái', 70, 800000, 750000, '../Uploads/img10.jpg', 5, 3, 'Active'),
('Nồi cơm điện Sony 1L', 'noi-com-dien-sony-1l', 'Nồi cơm điện Sony 1L, thiết kế đẹp, nấu cơm nhanh chóng.', 'Nồi cơm điện hiện đại', 55, 950000, 900000, '../Uploads/img11.jpg', 1, 3, 'Active'),
('Tủ lạnh Toshiba 250L', 'tu-lanh-toshiba-250l', 'Tủ lạnh Toshiba 250L, bảo quản thực phẩm tươi lâu, tiết kiệm điện.', 'Tủ lạnh tiết kiệm năng lượng', 45, 5500000, 5000000, '../Uploads/img12.jpg', 2, 5, 'Active'),
('Máy giặt Panasonic 9kg', 'may-giat-panasonic-9kg', 'Máy giặt Panasonic 9kg, giặt sạch mọi loại vải, tiết kiệm nước.', 'Máy giặt cao cấp', 30, 6000000, 5700000, '../Uploads/img13.jpg', 3, 1, 'Active'),
('Lò vi sóng Electrolux 22L', 'lo-vi-song-electrolux-22l', 'Lò vi sóng Electrolux 22L, dễ dàng sử dụng và làm nóng nhanh chóng.', 'Lò vi sóng tiện dụng', 40, 1300000, 1200000, '../Uploads/img14.jpg', 4, 4, 'Active'),
('Quạt điện Toshiba 20 inch', 'quat-dien-toshiba-20inch', 'Quạt điện Toshiba 20 inch, làm mát nhanh, bảo vệ sức khỏe người dùng.', 'Quạt điện cao cấp', 50, 700000, 650000, '../Uploads/img15.jpg', 5, 5, 'Active'),
('Máy pha cà phê Espresso Delonghi', 'may-pha-ca-phe-espresso-delonghi', 'Pha cà phê Espresso chuyên nghiệp tại nhà.', 'Máy pha cà phê Espresso tiện lợi', 20, 3500000, 3200000, '../Uploads/img16.jpg', 7, 1, 'Active'),
('Máy pha cà phê viên nén Nespresso', 'may-pha-ca-phe-vien-nen-nespresso', 'Sử dụng viên nén tiện lợi, pha nhanh, gọn.', 'Máy pha cà phê Nespresso sang trọng', 30, 4500000, 4100000, '../Uploads/img17.jpg', 7, 4, 'Active'),
('Máy pha cà phê tự động Philips HD7767', 'may-pha-ca-phe-philips-hd7767', 'Tích hợp chức năng xay và pha cà phê tự động.', 'Máy pha cà phê tự động 2 trong 1', 15, 5800000, 5200000, '../Uploads/img18.jpg', 7, 5, 'Active'),
('Máy pha cà phê Lock&Lock EJB', 'may-pha-ca-phe-locknlock-ejb', 'Thiết kế nhỏ gọn, tiện lợi cho văn phòng.', 'Máy pha cà phê cá nhân', 25, 1900000, 1700000, '../Uploads/img19.jpg', 7, 4, 'Active'),
('Máy pha cà phê mini Tiross TS621', 'may-pha-ca-phe-mini-tiross-ts621', 'Phù hợp sử dụng trong gia đình nhỏ.', 'Máy pha cà phê mini tiết kiệm diện tích', 20, 1350000, 1200000, '../Uploads/img20.jpg', 7, 3, 'Active'),
('Máy lọc nước RO Kangaroo KG100', 'may-loc-nuoc-ro-kangaroo-kg100', 'Lọc sạch vi khuẩn, bổ sung khoáng chất.', 'Máy lọc nước RO cao cấp', 20, 5200000, 4800000, '../Uploads/img21.jpg', 6, 2, 'Active'),
('Máy lọc nước Karofi Optimus Duo O-D138', 'may-loc-nuoc-karofi-optimus', 'Lọc nước 8 lõi, tích hợp màn hình hiển thị.', 'Máy lọc thông minh tích hợp', 18, 7300000, 6900000, '../Uploads/img22.jpg', 6, 3, 'Active'),
('Máy lọc nước Sunhouse SHA8858K', 'may-loc-nuoc-sunhouse-sha8858k', 'Thiết kế sang trọng, lọc đa tầng.', 'Máy lọc nước hiệu quả cao', 22, 6400000, 5900000, '../Uploads/img23.jpg', 6, 3, 'Active'),
('Máy lọc nước nóng lạnh AO Smith Z4', 'may-loc-nuoc-ao-smith-z4', 'Tích hợp nóng lạnh, thiết kế hiện đại.', 'Máy lọc nước 3 chế độ', 12, 8900000, 8500000, '../Uploads/img24.jpg', 6, 1, 'Active'),
('Máy lọc nước để bàn Xiaomi MR302', 'may-loc-nuoc-xiaomi-mr302', 'Dễ lắp đặt, tiết kiệm không gian.', 'Máy lọc mini để bàn', 30, 3500000, 3200000, '../Uploads/img25.jpg', 6, 1, 'Active');

