<?php

namespace App\Livewire;

use Livewire\Component;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class LandingPage extends Component
{
    use WithFileUploads;
    public $photos;
    public $imageInfos = [];
    public $allWidths = ['400','600','800','1200','1600','1800'];
    public $selectedWidths = [];
    public $availableWidths = [];
    public $noms = [];
    public $extensions = [];
    public $statut = '';


    protected $rules = [
        'photos.*' => 'required|image|max:5150',
        'selectedWidths' => 'required|array',
        'selectedWidths.*' => 'required|array|min:1',
    ];

    protected $messages = [
        'photos.*.required' => 'Chaque photo est requise.',
        'photos.*.image' => 'Chaque fichier doit être une image.',
        'photos.*.max' => 'Chaque image ne doit pas dépasser 5 Mo.',
        'selectedWidths.required' => 'Les sélections de largeur sont requises.',
        'selectedWidths.*.required' => 'Au moins une largeur doit être sélectionnée pour chaque image.',
        'selectedWidths.*.min' => 'Vous devez sélectionner au moins une largeur pour chaque image.',
    ];
    

    public function updatedPhotos(){
        $this->imageInfos = []; //Mettre à jour les infos à chaque preload
        $this->statut = ''; //Réinitialiser pour la prochaine upload
        $this->noms = [];

        if ($this->photos){
            foreach($this->photos as $index=>$photo){
                $this->imageInfos[] = getimagesize($photo->getRealPath());
                $this->noms[$index] = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
                $this->availableWidths[$index] = []; 
                $this->selectedWidths[$index] = [];
                
                //Filtering the available widths for each photo
                $imageWidth = $this->imageInfos[$index][0];
                $this->availableWidths[$index] = array_filter($this->allWidths,
                     function($width) use ($imageWidth) {   return $width <= $imageWidth;  });
            }
        }
    }

    public function scaleImage(){
        //Delete the images that can't be resized
        if ($this->photos){
            foreach($this->photos as $index=>$photo){
                if( empty($this->availableWidths[$index])){   
                    unset($this->photos[$index]); 
                    unset($this->noms[$index]);
                    unset($this->extensions[$index]);
                    unset($this->selectedWidths[$index]);
                    unset($this->availableWidths[$index]);

                }
            }
        }

         //Initialiser Intervention Image + validation
        $this->validate($this->rules, $this->messages);
        $manager = new ImageManager(new Driver());

        try {
            foreach($this->photos as $index=>$photo){
                $this->noms[$index] = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
                $this->extensions[$index] = $photo->getClientOriginalExtension();

                $photo->storeAs('photos', $this->noms[$index] . '.' . $this->extensions[$index], 'public'); 

                // Convert to .jpeg, store image, and destroy gd file to free memory
                if ($this->extensions[$index] == 'png') {
                    $image_gd = @imagecreatefrompng(storage_path('app/public/photos/'.$this->noms[$index].'.'.$this->extensions[$index]));
                    imagejpeg($image_gd, storage_path('app/public/photos/' . $this->noms[$index] . '.jpg'));
                    imagedestroy($image_gd);
                }

                $images[$index] = $manager->read(storage_path('app/public/photos/'.$this->noms[$index].'.'.$this->extensions[$index]));
                foreach ($this->selectedWidths[$index] as $width) {
                    $resizedImages[$index] = clone $images[$index];
                    $resizedImages[$index]->scale(width: $width);
                    $resizedImagesNames[$index] = $this->noms[$index] . '-' . $width . 'w.' . $this->extensions[$index];
                    $resizedImages[$index]->save(storage_path('app/public/photos_edited/' . $resizedImagesNames[$index]));
                }

                // if( file_exists(storage_path('app/public/photos_edited/'.$this->noms[$index].'-'.min($this->selectedWidths[$index]). 'w.' .$this->extensions[$index]))){
                //     $this->imagePreviews[] = Storage::url('photos_edited/'.$this->noms[$index].'-'.min($this->selectedWidths[$index]). 'w.' .$this->extensions[$index]);
                // }
            }

            $this->statut = 'Les images suivantes ont été redimensionnées avec succès :';
        } catch (\Exception $e) {   dump($e->getMessage()); }
    }

    public function downloadImages(){

        //Single Resized Image Exception 
        if( count($this->photos) == 1 && count($this->selectedWidths[count($this->photos)-1]) == 1){
            $resizedImageName = $this->noms[0] . '-' . $this->selectedWidths[0][0] . 'w.' . $this->extensions[0];
            return response()->download(storage_path('app/public/photos_edited/' . $resizedImageName))->deleteFileAfterSend(true);

        }else{
            //Multiple Images resized dans un fichier zip
            $zip = new \ZipArchive();
            $zipNom = 'ImageSizeify-'.time().'.zip';
            $zipChemin = storage_path('app/public/photos_edited/' . $zipNom);

            if ($zip->open($zipChemin, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === TRUE) {
                for ($index = 0; $index < count($this->photos); $index++) {
                    foreach ($this->selectedWidths[$index] as $width) {
                        $resizedImagesNames[$index] = $this->noms[$index] . '-' . $width . 'w.' .$this->extensions[$index];
                        $imageChemin[$index] = storage_path('app/public/photos_edited/' .$resizedImagesNames[$index]);

                        if (file_exists($imageChemin[$index])) {   $zip->addFile($imageChemin[$index], basename($imageChemin[$index])); }
                    }
                }
                $zip->close();  }

            // Delete the original image + resized images after creating the ZIP file
            for ($index = 0; $index < count($this->photos); $index++) {
                $originalImg = storage_path('app/public/photos/'.$this->noms[$index].'.'.$this->extensions[$index]);

                if(file_exists($originalImg)) {    unlink($originalImg);    }
                foreach ($this->selectedWidths[$index] as $width) {
                    $resizedImg = storage_path('app/public/photos_edited/' . $this->noms[$index] . '-' .$width.'w.'.$this->extensions[$index]);
                    if(file_exists($resizedImg)) {    unlink($resizedImg);  }
                }
            }

            return response()->download($zipChemin)->deleteFileAfterSend(true);
        }
    }
    
    public function render(){
        return view('livewire.landing-page');
    }
}
