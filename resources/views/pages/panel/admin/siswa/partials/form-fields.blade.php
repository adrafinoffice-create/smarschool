<div>
    <label class="form-label" for="kelas_id">Kelas</label>
    <select class="form-select" id="kelas_id" name="kelas_id" required>
        <option value="">Pilih kelas</option>
        @foreach ($kelas as $item)
            <option value="{{ $item->id }}" @selected(old('kelas_id', $siswa?->kelas_id) == $item->id)>{{ $item->nama_kelas }}</option>
        @endforeach
    </select>
    @error('kelas_id') <p class="mt-2 text-sm font-medium text-red-600">{{ $message }}</p> @enderror
</div>
<div>
    <label class="form-label" for="nis">NIS</label>
    <input class="form-input" id="nis" name="nis" type="text" value="{{ old('nis', $siswa?->nis) }}" required>
    @error('nis') <p class="mt-2 text-sm font-medium text-red-600">{{ $message }}</p> @enderror
</div>
<div>
    <label class="form-label" for="nama">Nama Lengkap</label>
    <input class="form-input" id="nama" name="nama" type="text" value="{{ old('nama', $siswa?->nama) }}" required>
    @error('nama') <p class="mt-2 text-sm font-medium text-red-600">{{ $message }}</p> @enderror
</div>
<div>
    <label class="form-label" for="jenis_kelamin">Jenis Kelamin</label>
    <select class="form-select" id="jenis_kelamin" name="jenis_kelamin" required>
        <option value="">Pilih jenis kelamin</option>
        <option value="Laki-laki" @selected(old('jenis_kelamin', $siswa?->jenis_kelamin) === 'Laki-laki')>Laki-laki</option>
        <option value="Perempuan" @selected(old('jenis_kelamin', $siswa?->jenis_kelamin) === 'Perempuan')>Perempuan</option>
    </select>
    @error('jenis_kelamin') <p class="mt-2 text-sm font-medium text-red-600">{{ $message }}</p> @enderror
</div>
<div>
    <label class="form-label" for="tempat_lahir">Tempat Lahir</label>
    <input class="form-input" id="tempat_lahir" name="tempat_lahir" type="text" value="{{ old('tempat_lahir', $siswa?->tempat_lahir) }}" required>
    @error('tempat_lahir') <p class="mt-2 text-sm font-medium text-red-600">{{ $message }}</p> @enderror
</div>
<div>
    <label class="form-label" for="tanggal_lahir">Tanggal Lahir</label>
    <input class="form-input" id="tanggal_lahir" name="tanggal_lahir" type="date" value="{{ old('tanggal_lahir', $siswa?->tanggal_lahir) }}" required>
    @error('tanggal_lahir') <p class="mt-2 text-sm font-medium text-red-600">{{ $message }}</p> @enderror
</div>
<div>
    <label class="form-label" for="nama_orang_tua">Nama Orang Tua</label>
    <input class="form-input" id="nama_orang_tua" name="nama_orang_tua" type="text" value="{{ old('nama_orang_tua', $siswa?->nama_orang_tua) }}" required>
    @error('nama_orang_tua') <p class="mt-2 text-sm font-medium text-red-600">{{ $message }}</p> @enderror
</div>
<div>
    <label class="form-label" for="no_hp">Nomor HP</label>
    <input class="form-input" id="no_hp" name="no_hp" type="text" value="{{ old('no_hp', $siswa?->no_hp) }}" required>
    @error('no_hp') <p class="mt-2 text-sm font-medium text-red-600">{{ $message }}</p> @enderror
</div>
<div class="md:col-span-2">
    <label class="form-label" for="alamat">Alamat</label>
    <textarea class="form-input" id="alamat" name="alamat" rows="4" required>{{ old('alamat', $siswa?->alamat) }}</textarea>
    @error('alamat') <p class="mt-2 text-sm font-medium text-red-600">{{ $message }}</p> @enderror
</div>
