<div x-data="{ progress:'', open:$wire.entangle('download') }" class="subpixel-antialiased">

    <div class="max-w-5xl m-auto bg-white p-3 rounded-lg my-3 shadow-lg">
        <article class="max-w-xl mx-auto flex flex-col items-center p-2 gap-1 font-barlow">
            <h3 class="text-3xl text-center font-semibold bg-clip-text text-transparent bg-gradient-to-r from-cyan-500 to-darkShade py-2">Image Sizeify</h3>
            <p class="text-center py-2 text-lg ">Upload our photos, and we'll adapt them for your website where you'll download all the different formats of your pictures adapted to multiple devices.</p>
        </article>
    
        <div x-on:livewire-upload-progress="progress = $event.detail.progress" class="w-full flex flex-row max-md:flex-col justify-center align-center p-3 gap-3">
            <form wire:submit.prevent="scaleImage" enctype="multipart/form-data" class="w-full flex flex-col justify-center items-center gap-2">
                <h4 class="text-lg font-semibold text-darkShade underline font-barlow">Upload Image :</h4>
    
                <div class="max-w-2xl mx-auto flex items-center justify-center w-full font-barlow">
                    <label for="photo" class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-bray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                            <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                            </svg>
                            <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">SVG, PNG, JPG or GIF (MAX. 20Mo)</p>
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
    
                <button type="submit" class="flex flex-row items-center py-2 px-6 bg-gradient-to-r  from-darkShade to-mediumShade hover:saturate-150 rounded shadow text-white font-semibold mt-4">
                    <svg width="26px" height="26px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M15 21H9C6.17157 21 4.75736 21 3.87868 20.1213C3 19.2426 3 17.8284 3 15M21 15C21 17.8284 21 19.2426 20.1213 20.1213C19.8215 20.4211 19.4594 20.6186 19 20.7487" stroke="#EEF7FF" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M12 16V3M12 3L16 7.375M12 3L8 7.375" stroke="#EEF7FF" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    <p class="ml-2">Upload</p>
                </button>
            </form>
            
            @if($statut)
                <div class="bg-white rounded-md shadow-md p-2">
                    <span class="text-center font-semibold text-darkShade">{{ $statut }}</span>
                    <div class="max-w-xl mx-auto flex flex-col items-center p-2 gap-1 font-barlow">
                        <button wire:click="downloadImages" class="flex flex-row items-center py-2 px-6 bg-gradient-to-r  from-darkShade to-mediumShade hover:saturate-150 rounded shadow text-white font-semibold mt-4">
                            <svg width="26px" height="26px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12.5535 16.5061C12.4114 16.6615 12.2106 16.75 12 16.75C11.7894 16.75 11.5886 16.6615 11.4465 16.5061L7.44648 12.1311C7.16698 11.8254 7.18822 11.351 7.49392 11.0715C7.79963 10.792 8.27402 10.8132 8.55352 11.1189L11.25 14.0682V3C11.25 2.58579 11.5858 2.25 12 2.25C12.4142 2.25 12.75 2.58579 12.75 3V14.0682L15.4465 11.1189C15.726 10.8132 16.2004 10.792 16.5061 11.0715C16.8118 11.351 16.833 11.8254 16.5535 12.1311L12.5535 16.5061Z" fill="#EEF7FF"/>
                                <path d="M3.75 15C3.75 14.5858 3.41422 14.25 3 14.25C2.58579 14.25 2.25 14.5858 2.25 15V15.0549C2.24998 16.4225 2.24996 17.5248 2.36652 18.3918C2.48754 19.2919 2.74643 20.0497 3.34835 20.6516C3.95027 21.2536 4.70814 21.5125 5.60825 21.6335C6.47522 21.75 7.57754 21.75 8.94513 21.75H15.0549C16.4225 21.75 17.5248 21.75 18.3918 21.6335C19.2919 21.5125 20.0497 21.2536 20.6517 20.6516C21.2536 20.0497 21.5125 19.2919 21.6335 18.3918C21.75 17.5248 21.75 16.4225 21.75 15.0549V15C21.75 14.5858 21.4142 14.25 21 14.25C20.5858 14.25 20.25 14.5858 20.25 15C20.25 16.4354 20.2484 17.4365 20.1469 18.1919C20.0482 18.9257 19.8678 19.3142 19.591 19.591C19.3142 19.8678 18.9257 20.0482 18.1919 20.1469C17.4365 20.2484 16.4354 20.25 15 20.25H9C7.56459 20.25 6.56347 20.2484 5.80812 20.1469C5.07435 20.0482 4.68577 19.8678 4.40901 19.591C4.13225 19.3142 3.9518 18.9257 3.85315 18.1919C3.75159 17.4365 3.75 16.4354 3.75 15Z" fill="#EEF7FF"/>
                                </svg>
                            <p class="ml-2">Download All</p>
                        </button>
                    </div>
            
                </div>
            @endif
        </div>
    </div>

    {{-- Download Section  --}}
    <section x-cloak x-show="open" class="max-w-5xl mx-auto bg-white p-3 rounded-lg my-3 shadow-lg subpixel-antialiased">
        <h4 class="text-lg text-center font-semibold text-darkShade underline font-barlow">Download Images :</h4>

        <div class="max-w-xl mx-auto flex flex-col items-center p-2 gap-1 font-barlow">
            <button wire:click="downloadImages" class="flex flex-row items-center py-2 px-6 bg-gradient-to-r  from-darkShade to-mediumShade hover:saturate-150 rounded shadow text-white font-semibold mt-4">
                <svg width="26px" height="26px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12.5535 16.5061C12.4114 16.6615 12.2106 16.75 12 16.75C11.7894 16.75 11.5886 16.6615 11.4465 16.5061L7.44648 12.1311C7.16698 11.8254 7.18822 11.351 7.49392 11.0715C7.79963 10.792 8.27402 10.8132 8.55352 11.1189L11.25 14.0682V3C11.25 2.58579 11.5858 2.25 12 2.25C12.4142 2.25 12.75 2.58579 12.75 3V14.0682L15.4465 11.1189C15.726 10.8132 16.2004 10.792 16.5061 11.0715C16.8118 11.351 16.833 11.8254 16.5535 12.1311L12.5535 16.5061Z" fill="#EEF7FF"/>
                    <path d="M3.75 15C3.75 14.5858 3.41422 14.25 3 14.25C2.58579 14.25 2.25 14.5858 2.25 15V15.0549C2.24998 16.4225 2.24996 17.5248 2.36652 18.3918C2.48754 19.2919 2.74643 20.0497 3.34835 20.6516C3.95027 21.2536 4.70814 21.5125 5.60825 21.6335C6.47522 21.75 7.57754 21.75 8.94513 21.75H15.0549C16.4225 21.75 17.5248 21.75 18.3918 21.6335C19.2919 21.5125 20.0497 21.2536 20.6517 20.6516C21.2536 20.0497 21.5125 19.2919 21.6335 18.3918C21.75 17.5248 21.75 16.4225 21.75 15.0549V15C21.75 14.5858 21.4142 14.25 21 14.25C20.5858 14.25 20.25 14.5858 20.25 15C20.25 16.4354 20.2484 17.4365 20.1469 18.1919C20.0482 18.9257 19.8678 19.3142 19.591 19.591C19.3142 19.8678 18.9257 20.0482 18.1919 20.1469C17.4365 20.2484 16.4354 20.25 15 20.25H9C7.56459 20.25 6.56347 20.2484 5.80812 20.1469C5.07435 20.0482 4.68577 19.8678 4.40901 19.591C4.13225 19.3142 3.9518 18.9257 3.85315 18.1919C3.75159 17.4365 3.75 16.4354 3.75 15Z" fill="#EEF7FF"/>
                    </svg>
                <p class="ml-2">Download All</p>
            </button>
        </div>

    </section>

</div>
