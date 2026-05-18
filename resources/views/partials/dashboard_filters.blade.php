<div class="mb-6 rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
        <div>
            <div class="text-lg font-semibold text-slate-800">Filter Dashboard</div>
            <div class="mt-1 text-sm text-slate-600">Percepat analisis berdasarkan karakteristik responden.</div>
        </div>
        <form method="GET" class="grid w-full grid-cols-2 gap-3 sm:grid-cols-3 lg:w-auto lg:max-w-3xl lg:grid-cols-6 lg:items-end">
            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700">Jenis Kelamin</label>
                <select name="gender" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-base">
                    <option value="">Semua</option>
                    <option value="male" @selected(request('gender') === 'male')>Laki-laki</option>
                    <option value="female" @selected(request('gender') === 'female')>Perempuan</option>
                </select>
            </div>
            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700">Usia Min</label>
                <input type="number" name="min_age" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-base" value="{{ request('min_age') }}">
            </div>
            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700">Usia Max</label>
                <input type="number" name="max_age" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-base" value="{{ request('max_age') }}">
            </div>
            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700">Status Disabilitas</label>
                <select name="is_disabled" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-base">
                    <option value="">Semua</option>
                    <option value="1" @selected(request('is_disabled') == '1')>Penyandang disabilitas</option>
                    <option value="0" @selected(request('is_disabled') == '0')>Non disabilitas</option>
                </select>
            </div>
            <div class="col-span-2 sm:col-span-1 lg:col-span-2">
                <button class="h-11 w-full rounded-lg bg-blue-600 text-base font-semibold text-white hover:bg-blue-700" type="submit">Terapkan</button>
            </div>
        </form>
    </div>
</div>
