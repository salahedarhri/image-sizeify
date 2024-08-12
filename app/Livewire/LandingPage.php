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
    public $allWidths = ['400','600','800','1000','1200','1400','1600','1800'];
    public $selectedWidths = [];
    public $availableWidths = [];
    public $noms = [];
    public $extensions = [];
    public $codes = [];
    public $statut = '';


    protected $rules = [
        'photos.*' => 'required|image|max:5150',
        'selectedWidths' => 'required|array',
        'selectedWidths.*' => 'required|array|min:1',
    ];

    protected $messages = [
        'photos.*.required' => 'Each photo is required.',
        'photos.*.image' => 'Each file must be an image.',
        'photos.*.max' => 'Each image must not exceed 5 MB.',
        'selectedWidths.required' => 'Width selections are required.',
        'selectedWidths.*.required' => 'At least one width must be selected for each image.',
        'selectedWidths.*.min' => 'You must select at least one width for each image.',
    ];
    

    public function updatedPhotos(){
        $this->imageInfos = []; //Mettre à jour les infos à chaque preload
        $this->statut = ''; //Réinitialiser pour la prochaine upload
        $this->noms = [];
        unset($this->codes);

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

        // Réindexer
        $this->photos = array_values($this->photos);
        $this->noms = array_values($this->noms);
        $this->extensions = array_values($this->extensions);
        $this->selectedWidths = array_values($this->selectedWidths);
        $this->availableWidths = array_values($this->availableWidths);

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

                $srcset = [];

                foreach ($this->selectedWidths[$index] as $width) {
                    $resizedImages[$index] = clone $images[$index];
                    $resizedImages[$index]->scale(width: $width);
                    $resizedImagesNames[$index] = $this->noms[$index] . '-' . $width . 'w.' . $this->extensions[$index];
                    $resizedImages[$index]->save(storage_path('app/public/photos_edited/' . $resizedImagesNames[$index]));

                    $srcset[] = "images/".$resizedImagesNames[$index]." ".$width."w";
                }

                //Code Section :
                $srcsetString = implode(', ', $srcset);
                $minWidth = min($this->selectedWidths[$index]);
                $this->codes[$index] = "<img src='images/" . $this->noms[$index] . "-" . $minWidth . "w." . $this->extensions[$index] . "' srcset='" . $srcsetString . "' alt='" . $this->noms[$index] . "'>";
            }
                $this->statut = 'Images have been resized successfully !';

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
        return view('livewire.landing-page',[
        ]);
    }
}
