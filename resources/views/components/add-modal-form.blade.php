<div class="modal fade" id="addmodal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title">{{ $title }}</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form action="{{ $action }}" method="post">
                @csrf

                <div class="modal-body">

                    <div class="mb-3">
                        <label class="form-label">Tahun Ajaran</label>
                        <input type="text" name="tahun_ajaran" class="form-control" value="{{ old('tahun_ajaran') }}"
                            required>
                    </div>

                    <div>
                        <label class="form-label">Semester</label>
                        <select name="semester" class="form-select">
                            <option value="" disabled selected>-- Pilih Semester --</option>
                            <option value="Ganjil" @selected(old('semester') == 'Ganjil')>Ganjil</option>
                            <option value="Genap" @selected(old('semester') == 'Genap')>Genap</option>
                        </select>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-sm">
                        Simpan
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>
