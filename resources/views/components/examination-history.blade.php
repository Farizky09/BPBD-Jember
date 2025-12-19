@props(['examination'])

<div class="grid grid-cols-4 items-center mb-3">
    <div>
        <p>{{ date('d/m/Y', strtotime($examination->created_at)) }}</p>
        <p class="text-gray-500">
            {{ \Carbon\Carbon::parse($examination->created_at)->locale('id')->isoFormat('H:mm') }} WIB
        </p>
    </div>
    <p>{{ $examination->doctor_name }}</p>
    <p>{{ $examination->branch_name }}</p>
    @if ($examination->poli_id == 2)
        @if ($examination->is_from_examinations_old)
            <a href="{{ route('doctor.examinations.old.show', $examination->id) }}"
                class="text-center text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-full text-sm px-5 py-2.5 w-fit">
                Lihat Detail
            </a>
        @else
            <a href="{{ route('doctor.examinations.show', $examination->id) }}"
                class="text-center text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-full text-sm px-5 py-2.5 w-fit">
                Lihat Detail
            </a>
        @endif
    @endif

    @if ($examination->poli_id == 3)
        <a href="{{ route('doctor.poli-umum.examinations.show', $examination->id) }}"
            class="text-center text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-full text-sm px-5 py-2.5 w-fit">
            Lihat Detail
        </a>
    @endif
</div>
