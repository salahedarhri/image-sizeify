<article x-data="{ progress:'' }" class="subpixel-antialiased">

    <div class="max-w-3xl m-auto bg-white p-3 rounded-lg my-3 shadow-lg">
        <section class="max-w-xl mx-auto flex flex-col items-center p-2 gap-1 font-barlow">
            <h1 class="text-3xl text-center font-semibold bg-clip-text text-transparent bg-gradient-to-r from-cyan-500 to-darkShade py-2">Image Sizeify</h3>
            <p class="text-center py-2 text-lg ">Upload our photos, and we'll adapt them for your website where you'll download all the different formats of your pictures adapted to multiple devices.</p>
        </section>
    
        <section x-on:livewire-upload-progress="progress = $event.detail.progress" class="w-full flex flex-col justify-center align-center p-3 gap-3">
            <form wire:submit.prevent="scaleImage" enctype="multipart/form-data" class="w-full flex flex-col justify-center items-center gap-2">
                <h4 class="text-lg font-semibold text-darkShade underline font-barlow">Upload Image :</h4>
    
                <div class="max-w-2xl mx-auto flex items-center justify-center w-full font-barlow">
                    <label for="photos" class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-bray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                            <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                            </svg>
                            <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">SVG, PNG, JPG or GIF (MAX. 5Mo)</p>
                        </div>
                        <input id="photos" type="file" class="hidden" accept="image/png, image/jpeg" wire:model.live="photos" name="photos" id="photos" multiple/>
                    </label>
                </div> 
                @error('photos.*')<p class="text-red-500 font-semibold text-sm p-1">{{ $message }}</p>@enderror
                <p wire:loading wire:target="photos" class="text-teal-600 font-semibold text-sm p-1">Chargement...<span x-text="progress"></span>%</p>
    
                @if($photos)
                    <h4 class="font-semibold text-darkShade underline font-barlow">Preview :</h4>
                    @foreach ($photos as $index => $photo)
                        <div class="max-w-2xl max-sm:w-full flex flex-row max-sm:flex-col items-center gap-2 bg-green-500 bg-opacity-10 border-2 border-green-600 border-opacity-10 rounded-lg px-2">
                            <img class="min-w-md h-36 mx-auto shadow-lg mt-3" src="{{ $photo->temporaryUrl() }}" alt="Uploaded photo">
                            @if(isset($imageInfos[$index]))
                                <div class="flex flex-col p-2 gap-1 font-barlow">
                                    <span class="underline font-semibold text-green-700">Image infos :</span>
                                    <span class="text-sm truncate"><b class="font-semibold text-green-700 truncate">&#9656; Nom :</b> {{ $noms[$index] }}</span>
                                    <span class="text-sm"><b class="font-semibold text-green-700">&#9656; Type :</b> {{ $imageInfos[$index]['mime'] }}</span>
                                    <span class="text-sm"><b class="font-semibold text-green-700">&#9656; Resolution :</b> {{ $imageInfos[$index][0] }} x {{ $imageInfos[$index][1] }} pixels</span>
                                    @if($availableWidths[$index])
                                        <div class="flex flex-col p-2 gap-1 pb-2">
                                            <span class="underline font-semibold text-green-700">Available Widths :</span>
                                            <div class="flex flex-col items-center gap-2 p-2">
                                                @foreach ($availableWidths[$index] as $width)
                                                    <input wire:key="width-{{ $index }}-{{ $width }}" id="widths" wire:model.live="selectedWidths.{{ $index }}" type="checkbox" value="{{ $width }}" class="w-4 h-4 shadow max-sm:w-5 max-sm:h-5 text-mediumShade bg-gray-100 border-gray-300 rounded focus:ring-mediumShade dark:focus:ring-mediumShade dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"/>
                                                    <label for="selectedWidths-{{ $index }}-{{ $width }}" class="text-sm text-gray-900 dark:text-gray-300 font-barlow">{{ $width }}px</label>
                                                @endforeach
                                            </div>
                                            @error('selectedWidths.*.*')<p class="text-red-500 font-semibold text-sm p-1">{{ $message }}</p>@enderror
                                            @error('selectedWidths')<p class="text-red-500 font-semibold text-sm p-1">{{ $message }}</p>@enderror
                                            @error('selectedWidths.*')<p class="text-red-500 font-semibold text-sm p-1">{{ $message }}</p>@enderror
                                        </div>
                                    @else
                                        <div class="flex flex-col p-2 gap-1 pb-2">
                                            <span class="underline text-black decoration-cyan-500">Your image is already optimized.</span>
                                            @error('selectedWidths.*.*')<p class="text-red-500 font-semibold text-sm p-1">{{ $message }}</p>@enderror
                                            @error('selectedWidths')<p class="text-red-500 font-semibold text-sm p-1">{{ $message }}</p>@enderror
                                            @error('selectedWidths.*')<p class="text-red-500 font-semibold text-sm p-1">{{ $message }}</p>@enderror
                                        </div>  
                                    @endif
                                </div>
                            @endif
                        </div>
                    @endforeach
                @endif
    
                <button type="submit" class="flex flex-row items-center py-2 px-6 bg-gradient-to-r  from-darkShade to-mediumShade hover:saturate-150 rounded shadow text-white font-semibold mt-4">
                    <svg width="26px" height="26px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M15 21H9C6.17157 21 4.75736 21 3.87868 20.1213C3 19.2426 3 17.8284 3 15M21 15C21 17.8284 21 19.2426 20.1213 20.1213C19.8215 20.4211 19.4594 20.6186 19 20.7487" stroke="#EEF7FF" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M12 16V3M12 3L16 7.375M12 3L8 7.375" stroke="#EEF7FF" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    <p class="uppercase ml-2">Upload</p>
                </button>
            </form>
            
            @if($statut)
                <div class="max-w-2xl mx-auto flex flex-col gap-1 items-center border-2 bg-cyan-100 border-cyan-200 rounded-md shadow-md p-3 mt-2">
                    <span class="text-center font-semibold text-darkShade p-1">{{ $statut }}</span>
                    @foreach($noms as $index => $nom)
                        <ul class="text-black font-barlow text-sm">
                            {{-- <li><img src="{{ $preview }}" alt="{{ $preview }}"></li> --}}
                            <li><span class="text-darkShade font-semibold underline">Nom :</span> {{ $nom }}</li>
                            <li><span class="text-darkShade font-semibold underline">Widths :</span> @foreach($selectedWidths[$index] as $width) &#11049;&nbsp;{{ $width }} px @endforeach</li>
                        </ul>
                    @endforeach
                    <div class="max-w-xl mx-auto flex flex-col items-center p-2 gap-1 font-barlow">
                        <button wire:click="downloadImages" class="flex flex-row items-center py-2 px-6 bg-gradient-to-r  from-darkShade to-mediumShade hover:saturate-150 rounded shadow text-white font-semibold mt-4">
                            <svg width="26px" height="26px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12.5535 16.5061C12.4114 16.6615 12.2106 16.75 12 16.75C11.7894 16.75 11.5886 16.6615 11.4465 16.5061L7.44648 12.1311C7.16698 11.8254 7.18822 11.351 7.49392 11.0715C7.79963 10.792 8.27402 10.8132 8.55352 11.1189L11.25 14.0682V3C11.25 2.58579 11.5858 2.25 12 2.25C12.4142 2.25 12.75 2.58579 12.75 3V14.0682L15.4465 11.1189C15.726 10.8132 16.2004 10.792 16.5061 11.0715C16.8118 11.351 16.833 11.8254 16.5535 12.1311L12.5535 16.5061Z" fill="#EEF7FF"/>
                                <path d="M3.75 15C3.75 14.5858 3.41422 14.25 3 14.25C2.58579 14.25 2.25 14.5858 2.25 15V15.0549C2.24998 16.4225 2.24996 17.5248 2.36652 18.3918C2.48754 19.2919 2.74643 20.0497 3.34835 20.6516C3.95027 21.2536 4.70814 21.5125 5.60825 21.6335C6.47522 21.75 7.57754 21.75 8.94513 21.75H15.0549C16.4225 21.75 17.5248 21.75 18.3918 21.6335C19.2919 21.5125 20.0497 21.2536 20.6517 20.6516C21.2536 20.0497 21.5125 19.2919 21.6335 18.3918C21.75 17.5248 21.75 16.4225 21.75 15.0549V15C21.75 14.5858 21.4142 14.25 21 14.25C20.5858 14.25 20.25 14.5858 20.25 15C20.25 16.4354 20.2484 17.4365 20.1469 18.1919C20.0482 18.9257 19.8678 19.3142 19.591 19.591C19.3142 19.8678 18.9257 20.0482 18.1919 20.1469C17.4365 20.2484 16.4354 20.25 15 20.25H9C7.56459 20.25 6.56347 20.2484 5.80812 20.1469C5.07435 20.0482 4.68577 19.8678 4.40901 19.591C4.13225 19.3142 3.9518 18.9257 3.85315 18.1919C3.75159 17.4365 3.75 16.4354 3.75 15Z" fill="#EEF7FF"/>
                                </svg>
                            <p class="uppercase ml-2">Download All</p>
                        </button>
                    </div>
                </div>
            @endif
        </section>

        <section class="w-full flex flex-col justify-center align-center p-3 gap-3">
            
        </section>
    </div>

</article>
