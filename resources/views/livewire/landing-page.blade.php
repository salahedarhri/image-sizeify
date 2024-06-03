<div x-data="{ progress:'' }" class="max-w-5xl m-auto bg-white p-3 rounded-lg my-3 shadow-lg subpixel-antialiased">

    <article class="max-w-xl mx-auto flex flex-col items-center p-2 gap-1 font-barlow">
        <h3 class="text-3xl text-center font-semibold bg-clip-text text-transparent bg-gradient-to-r from-cyan-500 to-darkShade py-2">Image Sizeify</h3>
        <p class="text-center py-2 text-lg ">Upload our photos, and we'll adapt them for your website where you'll download all the different formats of your pictures adapted to multiple devices.</p>
    </article>

    <div x-on:livewire-upload-progress="progress = $event.detail.progress" class="w-full flex flex-row max-md:flex-col justify-center align-center p-3 gap-3">
        <form 
            {{-- wire:loading.remove wire:target="scaleImage" wire:submit.prevent="scaleImage" --}}
            enctype="multipart/form-data" class="w-full flex flex-col justify-center items-center gap-2">
            <h4 class="font-semibold text-darkShade underline font-barlow">Upload Image :</h4>

            <div class="max-w-2xl mx-auto flex items-center justify-center w-full">
                <label for="photo" class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-bray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600">
                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                        <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                        </svg>
                        <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">SVG, PNG, JPG or GIF (MAX. 800x400px)</p>
                    </div>
                    <input id="photo" type="file" class="hidden" accept="image/png, image/jpeg" type="file" wire:model="photo" name="photo" id="photo"/>
                </label>
            </div> 
            @error('photo')<p class="text-red-500 font-semibold text-sm p-1">{{ $message }}</p>@enderror

            
            <p wire:loading wire:target="photo" class="text-teal-600 font-semibold text-sm p-1">Chargement...<span x-text="progress"></span>%</p>

            @if( $photo )
            <h4 class="font-semibold text-darkShade underline font-barlow">Preview :</h4>
            <div class="max-w-2xl grid grid-cols-2 max-md:grid-cols-1 gap-3 bg-green-500 bg-opacity-10 border-2 border-green-600 border-opacity-10 rounded-lg p-2 mb-4">
                <img class="h-36 mx-auto rounded-lg" src="{{ $photo->temporaryUrl() }}" alt="Uploaded photo">     
                @if ($imageInfo)
                    <div class="flex flex-col p-2 gap-1 font-barlow">
                        <span class="underline font-semibold text-green-700">Image infos :</span>
                        <span class="text-sm"><b class="font-semibold text-green-700">Resolution :</b> {{ $imageInfo[0] }} x {{ $imageInfo[1] }} pixels</span>
                        <span class="text-sm"><b class="font-semibold text-green-700">Type:</b> {{ image_type_to_mime_type($imageInfo[2]) }}</span>
                    </div>
                @endif
            </div>
            @endif

            @if($availableWidths)
                <h4 class="font-semibold text-darkShade underline font-barlow">Available Widths <i>(pixels)</i>:</h4>
                <label for="widths" class="flex flex-row max-sm:flex-col gap-3 p-1 items-center">
                    @foreach ($availableWidths as $w)
                    <div class="flex items-center pb-2">
                        <input id="widths" wire:model="widths" type="checkbox" value="{{ $w }}" class="w-4 h-4 max-sm:w-5 max-sm:h-5 text-mediumShade bg-gray-100 border-gray-300 rounded focus:ring-mediumShade dark:focus:ring-mediumShade dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"/>
                        <label for="widths" class="ms-2 font-medium text-gray-900 dark:text-gray-300 font-barlow">{{ $w }}px</label>
                    </div>
                    @endforeach
                </label>
            @endif


            <input type="submit" value="Upload" class="py-2 px-6 bg-gradient-to-r  from-darkShade to-mediumShade hover:saturate-150 rounded shadow text-white font-semibold mt-4">
        </form>
        
        @if($statut)
            <div class="bg-white rounded-md shadow-md p-2">
                <span class="text-center font-semibold text-darkShade">{{ $statut }}</span>
            </div>
        @endif
    </div>



</div>
