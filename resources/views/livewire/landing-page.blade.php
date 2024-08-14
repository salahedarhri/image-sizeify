<article x-data="{ progress:''}" class="subpixel-antialiased">
    <div class="w-full mx-auto">
        <section class="max-w-xl mx-auto flex flex-col items-center p-2 gap-1 ">
            <h1 class="text-3xl text-center font-semibold text-darkShade dark:text-whiteShade py-2 font-barlow mt-24">Image Sizeify</h1>
            <p class="text-center py-2 text-lg text-darkShade dark:text-whiteShade font-lato">Upload your photos, and we'll adapt them for your website where you'll download all the different formats of your pictures adapted to multiple devices.</p>
        </section>
    
        <section x-on:livewire-upload-progress="progress = $event.detail.progress" class="max-w-5xl mx-auto flex flex-col justify-center align-center p-3 gap-3 mb-12">
            
            <form wire:submit.prevent="scaleImage" enctype="multipart/form-data" class="w-full flex flex-col justify-center place-items-center gap-2">
                
                {{-- Drag & Drop Zone--}}
                <div x-data="drop_file_component()" class="max-w-3xl mx-auto flex items-center justify-center w-full font-barlow">
                    <label 
                    x-on:drop="droppingFile = false"
                    x-on:drop.prevent="handleFileDrop($event)"
                    x-on:dragover.prevent="droppingFile = true"
                    x-on:dragleave.prevent="droppingFile = false" 
                    for="photos" class="flex flex-col items-center justify-center w-full h-64 border-2 border-mediumShade dark:border-lightShade border-dashed rounded-lg cursor-pointer bg-lightShade dark:bg-mediumShade hover:saturate-150">
                        
                        {{-- Upload Interface --}}
                        <div  x-show="!droppingFile" class="flex flex-col items-center justify-center pt-5 pb-6">
                            <svg class="w-8 h-8 mb-4 text-darkShade dark:text-whiteShade" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                            </svg>
                            <p class="mb-2 text-sm text-darkShade dark:text-whiteShade"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                            <p class="text-xs text-darkShade dark:text-whiteShade"> JPG, JPEG, PNG, BMP, SVG & WEBP (MAX. 5 Mo)</p>
                        </div>

                        {{-- Uploading Interface --}}
                        <div x-show="droppingFile" class="flex flex-col items-center justify-center pt-5 pb-6">
                            <svg class="w-8 h-8 mb-4 text-darkShade dark:text-whiteShade" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                            </svg>
                            <p class="mb-2 text-sm text-darkShade dark:text-whiteShade"><span class="font-semibold">Drop it here !</p>
                        </div>
                        <input id="photos" type="file" class="hidden" wire:model.live="photos" multiple/>
                    </label>
                </div> 
                
                @error('photos.*')<p class="text-red-600 dark:text-red-400 font-semibold text-sm p-1">{{ $message }}</p>@enderror
                
                <p wire:loading wire:target="photos" class="text-darkShade dark:text-lightShade font-semibold text-sm p-1">Chargement...<span x-text="progress"></span>%</p>
    
                {{-- Pictures Informations --}}
                @if($photos)
                    <h4 class="font-semibold text-darkShade dark:text-whiteShade underline font-barlow">Preview :</h4>
                    
                    @foreach ($photos as $index => $photo)
                        <div class="max-w-3xl w-full flex flex-row items-center gap-2 bg-mediumShade bg-opacity-10 dark:bg-mediumShade border-2 border-mediumShade dark:border-lightShade dark:bg-opacity-20 dark:border-opacity-20 rounded-lg px-2">
                            <img class="w-40 max-md:w-20 object-contain object-center mt-3" src="{{ $photo->temporaryUrl() }}" alt="Uploaded photo">
                            
                            @if(isset($imageInfos[$index]))
                                <div class="w-full flex flex-col p-2 gap-1 font-barlow text-darkShade dark:text-whiteShade">
                                    <span class="underline font-semibold ">Image infos :</span>
                                    <span class="text-sm truncate"><b class="font-semibold">&#9656; Nom :</b> {{ $noms[$index] }}</span>
                                    <span class="text-sm"><b class="font-semibold">&#9656; Type :</b> {{ $imageInfos[$index]['mime'] }}</span>
                                    <span class="text-sm"><b class="font-semibold">&#9656; Resolution :</b> {{ $imageInfos[$index][0] }} x {{ $imageInfos[$index][1] }} pixels</span>

                                    @if($availableWidths[$index])
                                        <div class="flex flex-col p-2 gap-1 pb-2">
                                            <span class="underline font-semibold">Available Widths :</span>
                                            <div class="grid grid-cols-4 items-center gap-3 p-2">
                                                @foreach ($availableWidths[$index] as $width)
                                                    <label for="selectedWidths.{{ $index }}.{{ $width }}" class="text-sm text-darkShade dark:text-whiteShade font-barlow">
                                                        <input wire:key="width-{{ $index }}-{{ $width }}" id="widths" wire:model.live="selectedWidths.{{ $index }}" type="checkbox" value="{{ $width }}" 
                                                        class="w-4 h-4 shadow max-sm:w-5 max-sm:h-5 text-mediumShade dark:text-lightShade bg-whiteShade dark:bg-darkShade border-mediumShade dark:border-lightShade rounded focus:ring-mediumShade dark:focus:ring-lightShade focus:ring-2 mr-2">
                                                        {{ $width }}px
                                                    </label>
                                                @endforeach
                                            </div>
                                            @error('selectedWidths.*.*')<p class="text-red-600 dark:text-red-400 font-semibold text-sm p-1">{{ $message }}</p>@enderror
                                            @error('selectedWidths')<p class="text-red-600 dark:text-red-400 font-semibold text-sm p-1">{{ $message }}</p>@enderror
                                            @error('selectedWidths.*')<p class="text-red-600 dark:text-red-400 font-semibold text-sm p-1">{{ $message }}</p>@enderror
                                        </div>
                                    @else
                                        <div class="flex flex-col p-2 gap-1 pb-2">
                                            <span class="underline text-darkhade dark:text-whiteShade">Your image is already optimized.</span>
                                            @error('selectedWidths.*.*')<p class="text-red-600 dark:text-red-400 font-semibold text-sm p-1">{{ $message }}</p>@enderror
                                            @error('selectedWidths')<p class="text-red-600 dark:text-red-400 font-semibold text-sm p-1">{{ $message }}</p>@enderror
                                            @error('selectedWidths.*')<p class="text-red-600 dark:text-red-400 font-semibold text-sm p-1">{{ $message }}</p>@enderror
                                        </div>  
                                    @endif
                                </div>
                            @endif
                        </div>
                    @endforeach

                    <button type="submit" class="font-barlow flex flex-row place-items-center py-2 px-6 bg-gradient-to-r from-darkShade to-mediumShade hover:saturate-150 dark:from-lightShade dark:to-whiteShade rounded shadow text-whiteShade dark:text-darkShade font-semibold mt-4 mx-auto">
                        <svg width="26px" height="26px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M15 21H9C6.17157 21 4.75736 21 3.87868 20.1213C3 19.2426 3 17.8284 3 15M21 15C21 17.8284 21 19.2426 20.1213 20.1213C19.8215 20.4211 19.4594 20.6186 19 20.7487" stroke="#EEF7FF" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M12 16V3M12 3L16 7.375M12 3L8 7.375" stroke="#EEF7FF" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <p class="uppercase ml-2">Resize</p>
                    </button>
                @endif

            </form>

            {{-- Download Section --}}
            @if($statut)
                <div class="max-w-3xl w-full mx-auto flex flex-col gap-1 items-center border-2 bg-lightShade dark:bg-darkShade border-mediumShade dark:border-whiteShade rounded-md shadow-md p-3 mt-2">
                    <span class="text-center font-semibold text-darkShade dark:text-whiteShade p-1">{{ $statut }}</span>
                    @foreach($noms as $index => $nom)
                        <ul class="text-darkShade dark:text-whiteShade font-barlow text-md w-full flex flex-col gap-1 p-3">
                            <li><span class="font-semibold">Image :</span> {{ $nom }}</li>
                            <li><span class="font-semibold">Width(s) :</span> @foreach($selectedWidths[$index] as $width) &#11049;&nbsp;{{ $width }} px @endforeach</li>
                            <li x-data="{ copied: false }" class="w-full flex flex-col justify-center items-center mt-2">
                                <div class="w-full flex flex-row justify-between rounded-t-md py-1 px-2 bg-darkShade dark:bg-mediumShade text-whiteShade text-sm">
                                    <span>Code</span>
                                    <button @click="navigator.clipboard.writeText($refs.code.innerText); copied = true; setTimeout(() => copied = false, 3000)"
                                        class="text-sm flex flex-row justify-center align-middle gap-1">
                                        <span class="font-semibold" x-show="!copied">Copy</span>
                                        <span class="font-semibold" x-show="copied" x-transition>Copied!</span>
                                        <svg class="w-6 h-6 text-whiteShade" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                            <path fill-rule="evenodd" d="M18 3a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-1V9a4 4 0 0 0-4-4h-3a1.99 1.99 0 0 0-1 .267V5a2 2 0 0 1 2-2h7Z" clip-rule="evenodd"/>
                                            <path fill-rule="evenodd" d="M8 7.054V11H4.2a2 2 0 0 1 .281-.432l2.46-2.87A2 2 0 0 1 8 7.054ZM10 7v4a2 2 0 0 1-2 2H4v6a2 2 0 0 0 2 2h7a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3Z" clip-rule="evenodd"/>
                                          </svg>                                          
                                    </button>
                                </div>
                                <div class="overflow-x-auto w-full bg-black p-3 rounded-b-lg text-whiteShade flex-grow">
                                    <pre><code x-ref="code" class="text-sm">{{  $codes[$index] }} </code></pre>
                                </div>

                            </li>
                        </ul>
                    @endforeach
                    <div class="flex flex-col items-center p-2 gap-1 font-barlow">
                        <button wire:click="downloadImages" class="flex flex-row items-center py-2 px-6 bg-gradient-to-r from-darkShade to-mediumShade hover:saturate-150 dark:from-lightShade dark:to-whiteShade rounded shadow text-whiteShade dark:text-darkShade font-semibold mt-4">
                            <svg width="26px" height="26px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12.5535 16.5061C12.4114 16.6615 12.2106 16.75 12 16.75C11.7894 16.75 11.5886 16.6615 11.4465 16.5061L7.44648 12.1311C7.16698 11.8254 7.18822 11.351 7.49392 11.0715C7.79963 10.792 8.27402 10.8132 8.55352 11.1189L11.25 14.0682V3C11.25 2.58579 11.5858 2.25 12 2.25C12.4142 2.25 12.75 2.58579 12.75 3V14.0682L15.4465 11.1189C15.726 10.8132 16.2004 10.792 16.5061 11.0715C16.8118 11.351 16.833 11.8254 16.5535 12.1311L12.5535 16.5061Z" fill="#EEF7FF"/>
                                    <path d="M3.75 15C3.75 14.5858 3.41422 14.25 3 14.25C2.58579 14.25 2.25 14.5858 2.25 15V15.0549C2.24998 16.4225 2.24996 17.5248 2.36652 18.3918C2.48754 19.2919 2.74643 20.0497 3.34835 20.6516C3.95027 21.2536 4.70814 21.5125 5.60825 21.6335C6.47522 21.75 7.57754 21.75 8.94513 21.75H15.0549C16.4225 21.75 17.5248 21.75 18.3918 21.6335C19.2919 21.5125 20.0497 21.2536 20.6517 20.6516C21.2536 20.0497 21.5125 19.2919 21.6335 18.3918C21.75 17.5248 21.75 16.4225 21.75 15.0549V15C21.75 14.5858 21.4142 14.25 21 14.25C20.5858 14.25 20.25 14.5858 20.25 15C20.25 16.4354 20.2484 17.4365 20.1469 18.1919C20.0482 18.9257 19.8678 19.3142 19.591 19.591C19.3142 19.8678 18.9257 20.0482 18.1919 20.1469C17.4365 20.2484 16.4354 20.25 15 20.25H9C7.56459 20.25 6.56347 20.2484 5.80812 20.1469C5.07435 20.0482 4.68577 19.8678 4.40901 19.591C4.13225 19.3142 3.9518 18.9257 3.85315 18.1919C3.75159 17.4365 3.75 16.4354 3.75 15Z" fill="#EEF7FF"/>
                            </svg>
                            <p class="uppercase ml-2">Download Images</p>
                        </button>
                    </div>
                </div>
            @endif
    
        </section>

        {{-- FAQ --}}
        <div class="w-full border-t border-lightShade dark:border-lightShade">
            <section class="max-w-5xl mx-auto max-md:p-4 md:py-12">
                <h1 class="text-3xl text-center font-semibold text-darkShade dark:text-whiteShade py-2 font-barlow mb-4">FAQ Section</h1>
    
                <div id="accordion-collapse" data-accordion="collapse" class="font-lato">
                    <h2 id="accordion-collapse-heading-1">
                        <button type="button" class="flex items-center justify-between w-full p-5 font-medium rtl:text-right text-darkShade dark:text-whiteShade border border-b-0 border-mediumShade dark:border-lightShade rounded-t-xl focus:ring-4 focus:ring-lightShade dark:focus:ring-mediumShade hover:bg-lightShade dark:hover:bg-black gap-3" data-accordion-target="#accordion-collapse-body-1" aria-expanded="true" aria-controls="accordion-collapse-body-1">
                            <span class="font-semibold">What is ImageSizeify?</span>
                            <svg data-accordion-icon class="w-3 h-3 rotate-180 shrink-0" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5 5 1 1 5"/>
                            </svg>
                        </button>
                    </h2>
                    <div id="accordion-collapse-body-1" class="hidden" aria-labelledby="accordion-collapse-heading-1">
                        <div class="p-5 border border-b-0 border-mediumShade dark:border-lightShade dark:bg-darkShade">
                            <p class="mb-2 text-darkShade dark:text-whiteShade">ImageSizeify is a powerful tool that allows users to resize images to specific dimensions, supporting multiple image formats. It offers predefined widths to choose from, making it easy to scale images for different use cases.It also provides the code adapted to it so the user can simply copy and paste it where needed.</p>
                        </div>
                    </div>
            
                    <h2 id="accordion-collapse-heading-2">
                        <button type="button" class="flex items-center justify-between w-full p-5 font-medium rtl:text-right text-darkShade dark:text-whiteShade border border-b-0 border-mediumShade dark:border-lightShade focus:ring-4 focus:ring-lightShade dark:focus:ring-mediumShade hover:bg-lightShade dark:hover:bg-black gap-3" data-accordion-target="#accordion-collapse-body-2" aria-expanded="false" aria-controls="accordion-collapse-body-2">
                            <span class="font-semibold">How does ImageSizeify work?</span>
                            <svg data-accordion-icon class="w-3 h-3 rotate-180 shrink-0" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5 5 1 1 5"/>
                            </svg>
                        </button>
                    </h2>
                    <div id="accordion-collapse-body-2" class="hidden" aria-labelledby="accordion-collapse-heading-2">
                        <div class="p-5 border border-b-0 border-mediumShade dark:border-lightShade dark:bg-darkShade">
                            <p class="mb-2 text-darkShade dark:text-whiteShade">ImageSizeify uses the Intervention Image library ( along with GD ) to handle image resizing. Users can upload images, select desired widths, and download the resized versions. The tool supports batch processing, allowing multiple images to be resized simultaneously.</p>
                        </div>
                    </div>
            
                    <h2 id="accordion-collapse-heading-3">
                        <button type="button" class="flex items-center justify-between w-full p-5 font-medium rtl:text-right text-darkShade dark:text-whiteShade border border-mediumShade dark:border-lightShade focus:ring-4 focus:ring-lightShade dark:focus:ring-mediumShade hover:bg-lightShade dark:hover:bg-black gap-3" data-accordion-target="#accordion-collapse-body-3" aria-expanded="false" aria-controls="accordion-collapse-body-3">
                            <span class="font-semibold">What are the benefits of using ImageSizeify?</span>
                            <svg data-accordion-icon class="w-3 h-3 rotate-180 shrink-0" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5 5 1 1 5"/>
                            </svg>
                        </button>
                    </h2>
                    <div id="accordion-collapse-body-3" class="hidden" aria-labelledby="accordion-collapse-heading-3">
                        <div class="p-5 border border-t-0 border-mediumShade dark:border-lightShade dark:bg-darkShade">
                            <p class="mb-2 text-darkShade dark:text-whiteShade">ImageSizeify is designed to be user-friendly and efficient. It saves time by providing predefined widths and allows users to resize images in bulk. It also supports multiple image formats, making it versatile for various projects.</p>
                        </div>
                    </div>
                </div>
            </section>
        </div>

    </div>
</article>

{{-- Drag & Drop functionality --}}
@push('custom-scripts')
    <script>
            function drop_file_component() {
                return {
                    droppingFile: false,
                    handleFileDrop(e) {
                        if (event.dataTransfer.files.length > 0) {
                            const files = e.dataTransfer.files;

                            //Drag images only
                            for( let file of files){
                                if (!file.type.match('image.*')) {
                                alert('Only jpeg & png images are supported')
                                return  }
                            }

                            @this.uploadMultiple('photos', files,
                                (uploadedFilename) => {}, () => {}, (event) => {}
                            )
                        }
                    }
                };
            }
    </script>
@endpush