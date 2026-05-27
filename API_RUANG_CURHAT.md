# API Ruang Curhat

Base URL production:

```text
https://arjuno.my.id/api
```

Base URL lokal Laravel:

```text
http://127.0.0.1:8000/api
```

Untuk Android emulator gunakan:

```text
http://10.0.2.2:8000/api
```

## Endpoint Utama

| Method | Path | Fungsi |
| --- | --- | --- |
| GET | `/health` | Cek API hidup |
| POST | `/auth/login` | Login akun dinas/admin |
| POST | `/auth/register` | Buat akun dinas baru |
| GET | `/auth/me` | Ambil profil dari token Bearer |
| POST | `/auth/logout` | Hapus token aktif |
| GET | `/counselors` | Semua konselor aktif |
| GET | `/counselors?religion=Islam` | Konselor berdasarkan agama |
| POST | `/counselors` | Tambah konselor |
| GET | `/counselors/{id}` | Detail konselor |
| PUT/PATCH | `/counselors/{id}` | Update konselor |
| DELETE | `/counselors/{id}` | Hapus konselor |

## Contoh Login

```json
{
  "email": "admin@gmail.com",
  "password": "admin123"
}
```

## Contoh Tambah Konselor

```json
{
  "name": "Nama Konselor",
  "pangkat": "KAPTEN (SUS)",
  "nrp": "5550001",
  "jabatan": "Pembimbing Rohani",
  "kesatuan": "Lanud Abdulrachman Saleh",
  "telegram": "username_telegram",
  "religion": "Islam"
}
```

Jalankan backend:

```bash
php artisan migrate --seed
php artisan serve --host=0.0.0.0 --port=8000
```
