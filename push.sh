#!/bin/bash
# =====================================================
# Git Auto Push Script (versi otomatis & aman)
# by ghost_shell 🧠
# =====================================================

# Pastikan ada pesan commit
if [ -z "$1" ]; then
    echo "⚠️  Gunakan: ./push.sh \"Update commit otomatis\""
    exit 1
fi

# Simpan pesan commit
MESSAGE="$1"

echo "🌀 Menyimpan perubahan ke GitHub..."
echo "Pesan commit: $MESSAGE"
echo "=========================================="

# Tambahkan semua perubahan
git add .

# Commit semua perubahan
git commit -m "$MESSAGE"

# Tarik update terbaru dari GitHub (pakai rebase biar rapi)
git pull origin master --rebase

# Kirim ke GitHub
git push origin master

# Tampilkan hasil
echo "=========================================="
echo "✅ Push berhasil! Repository sekarang sinkron."
git log --oneline --decorate -3

