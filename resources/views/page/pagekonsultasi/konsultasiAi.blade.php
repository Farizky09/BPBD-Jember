<div id="konsultasiAi"
    class="hidden flex flex-col md:flex-row items-center justify-center gap-6 w-full px-4 py-6 bg-white">

    <!-- Dropzone -->
    <label for="dropzone-file"
        class="flex flex-col items-center justify-center w-full max-w-md h-[300px] rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 dark:bg-gray-700 dark:hover:bg-gray-600 relative transition-all duration-200 shadow-md">
        <div id="dropzone-content" class="flex flex-col items-center justify-center pt-5 pb-6 z-10">
            <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
            </svg>
            <p class="mb-2 text-sm text-gray-500 dark:text-gray-400">
                <span class="font-semibold">Click to upload</span> or drag and drop
            </p>
            <p class="text-xs text-gray-500 dark:text-gray-400">SVG, PNG, JPG, or GIF (MAX. 600x300px)</p>
        </div>
        <div id="image-preview"
            class="absolute inset-0 hidden items-center justify-center rounded-lg overflow-hidden bg-black/20 z-20">
            <img id="preview-image" class="max-h-full max-w-full object-contain" alt="Preview" />
            <button onclick="removeImage()"
                class="absolute top-2 right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center hover:bg-red-600 z-30">
                ×
            </button>
        </div>
        <input id="dropzone-file" type="file" class="hidden" accept=".svg, .png, .jpeg, .jpg, .gif" />
    </label>

    <!-- Result Area -->
    <div class="w-full md:w-auto flex-shrink-0 max-w-md relative"> <!-- Tambahkan relative di sini -->

        <div id="result-card"
            class="w-full max-w-md h-[300px] p-4 border-2 border-gray-200 bg-gray-50 rounded-lg dark:bg-gray-100 dark:border-gray-400 overflow-y-auto min-h-[300px] shadow-sm">

            <!-- jenis -->
            <div class="w-full mb-4">
                <label class="font-bold text-blue-700">💡 Jenis Bencana:</label>
                <textarea id="jenis-textarea"
                    class="w-full h-12 p-2 mt-1 border-2 border-blue-100 bg-blue-50 rounded-lg dark:bg-blue-100 dark:border-blue-300 text-black placeholder-gray-500 resize-none"
                    style="text-align: justify; white-space: pre-line; font-family: inherit;" disabled
                    placeholder="Jenis bencana akan ditampilkan di sini..."></textarea>
            </div>

            <!-- dampak -->
            <div class="w-full mb-4">
                <label class="font-bold text-yellow-600 block mt-2">🚨 Dampak Bencana:</label>
                <textarea id="dampak-textarea"
                    class="w-full h-32 p-2 mt-1 border-2 border-yellow-50 bg-yellow-50 rounded-lg dark:bg-yellow-100 dark:border-yellow-300 text-black placeholder-gray-500 resize-none"
                    style="text-align: justify; white-space: pre-line; font-family: inherit;" disabled
                    placeholder="Dampak bencana akan ditampilkan di sini..."></textarea>
            </div>

            <!-- penanganan -->
            <div class="w-full">
                <label class="font-bold text-green-600 block mt-2">🛠️ Penanganan Bencana:</label>
                <textarea id="penanganan-textarea"
                    class="w-full h-32 p-2 mt-1 border-2 border-green-50 bg-green-50 rounded-lg dark:bg-green-100 dark:border-green-300 text-black placeholder-gray-500 resize-none"
                    style="text-align: justify; white-space: pre-line; font-family: inherit;" disabled
                    placeholder="Penanganan bencana akan ditampilkan di sini..."></textarea>
            </div>
        </div>

        <!-- ⏳ Loading overlay -->
        <div id="loading-icon"
            class="hidden absolute inset-0 flex items-center justify-center bg-white bg-opacity-80 rounded-lg z-30">
            <i class="fas fa-spinner fa-spin text-2xl text-blue-500"></i>
            <span class="ml-2 text-gray-700">Memproses gambar...</span>
        </div>

    </div>

</div>
