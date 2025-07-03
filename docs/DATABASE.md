## 📁 `docs/DATABASE.md`
```markdown
# 🗂 Struktur Database Photostrip Maulideas

Terdiri dari beberapa tabel utama berikut:

📄 Tabel Utama
- **users**: menyimpan data pengguna & admin
- **orders**: data pesanan user
- **produk**: daftar produk photostrip
- **desain**: model desain yang tersedia
- **pembayaran**: data bukti dan status pembayaran
- **pengiriman**: status pengiriman pesanan
- **blogs**: artikel untuk ditampilkan ke user
- **pelanggan**: data kontak pelanggan
- **laporan_admin**: catatan login admin
- **kartu_member**: info member user

🔗 Relasi Antar Tabel
- `orders.user_id` → `users.id`
- `orders.produk_id` → `produk.id`
- `desain.produk_id` → `produk.id`
- `pembayaran.order_id` → `orders.id`
- `pengiriman.order_id` → `orders.id`
- `blogs.author_id` → `users.id`
- `pelanggan.user_id` → `users.id`
- `laporan_admin.admin_id` → `users.id`
- `kartu_member.user_id` → `users.id`

🖼 Diagram ERD
> Lihat file: `docs/erd_web_photostrip_maulideas.pdf`
