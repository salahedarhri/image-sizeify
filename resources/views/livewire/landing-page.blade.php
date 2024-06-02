<div class="max-w-5xl m-auto bg-white p-3 rounded my-3 shadow">

    <article class="max-w-xl mx-auto flex flex-col items-center p-2 gap-1 font-lato">
        <h3 class="text-3xl text-center font-semibold bg-clip-text text-transparent bg-gradient-to-r from-cyan-500 to-darkShade py-2">Image Sizeify</h3>
        <p class="text-center py-2">Upload our photos, and we'll adapt them for your website where you'll download all the different formats of your pictures adapted to multiple devices.</p>
    </article>

    <div class="w-full flex flex-row max-md:flex-col justify-center align-center p-3 gap-3">
        <form wire:loading.remove wire:target="scaleImage" wire:submit.prevent="scaleImage" enctype="multipart/form-data" class="w-full flex flex-col justify-center items-center gap-2">
            <label for="photo" class="flex flex-col justify-center items-center gap-2 py-2 ">
                <h4 class="font-semibold text-darkShade underline">Upload Image :</h4>
                <input wire:loading.attr="disabled" wire:target="photo" accept="image/png, image/jpeg" type="file" wire:model="photo" name="photo" id="photo"
                 class="w-full file:text-white file:bg-darkShade file:border-none hover:saturate-150 file:transition rounded shadow font-lato">
                <p wire:loading wire:target="photo" class="text-teal-600 font-semibold text-sm p-1">Chargement...</p>
                @error('photo')<p class="text-red-500 font-semibold text-sm p-1">{{ $message }}</p>@enderror
            </label>
    
            @if( $photo )
                <img class="my-2 rounded-lg w-40 mx-auto" src="{{ $photo->temporaryUrl() }}" alt="Uploaded photo">    
            @endif
            <input type="submit" value="Upload" class="py-2 px-6 bg-gradient-to-r  from-darkShade to-mediumShade hover:saturate-150 rounded shadow text-white font-semibold">
        </form>
        
        @if($statut)
            <div class="bg-white rounded-md shadow-md p-2">
                <span class="text-center font-semibold text-darkShade">{{ $statut }}</span>
            </div>
        @endif

    </div>



</div>
