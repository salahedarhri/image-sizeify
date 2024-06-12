<?php

namespace App\Livewire;

use Livewire\Component;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Livewire\WithFileUploads;

class LandingPage extends Component
{
    use WithFileUploads;
    public $photos;
    public $imageInfos = [];
    public $statut = '';
    public $selectedWidths = [];
    public $allWidths = ['400','600','800','1200','1600'];
    public $availableWidths = [];
    public $download = false;
    public $noms = [];
    public $extensions = [];

    protected $rules = [
        'photos.*' => 'required|image|max:10400',
        'selectedWidths' => 'required|array',
        'selectedWidths.*' => 'required|array|min:1',
    ];

    protected $messages = [
        'photos.*.required' => 'Chaque photo est requise.',
        'photos.*.image' => 'Chaque fichier doit être une image.',
        'photos.*.max' => 'Chaque image ne doit pas dépasser 10 Mo.',
        'selectedWidths.required' => 'Les sélections de largeur sont requises.',
        'selectedWidths.*.required' => 'Au moins une largeur doit être sélectionnée pour chaque image.',
        'selectedWidths.*.min' => 'Vous devez sélectionner au moins une largeur pour chaque image.',
    ];
    

    public function updatedPhotos(){
        if ($this->photos){
            foreach($this->photos as $index=>$photo){
                $this->imageInfos[] = getimagesize($photo->getRealPath());
                $this->availableWidths[$index] = []; 
                $this->selectedWidths[$index] = [];
                
                //Filtering the available widths for each photo
                $imageWidth = $this->imageInfos[$index][0];
                $this->availableWidths[$index] = array_filter($this->allWidths,
                     function($width) use ($imageWidth) {
                        return $width <= $imageWidth;
                    });
            }
        }
    }

    public function scaleImage(){
        $this->validate($this->rules, $this->messages);
        $manager = new ImageManager(new Driver()); //Initialiser Intervention Image

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
            }
            
            $this->statut = 'Toutes les images ont été redimensionnées avec succès !';
        } catch (\Exception $e) {   dump($e->getMessage()); }
    }
    

    public function downloadImages(){
        if ($this->availableWidths) {
            if (sizeof($this->availableWidths) > 1) {
                $zip = new \ZipArchive();
                $zipFileName = 'ImageSizeify-'.$this->nom.'-'.time().'.zip';
                $zipFilePath = storage_path('app/public/photos_edited/' . $zipFileName);
    
                if ($zip->open($zipFilePath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === TRUE) {
                    foreach ($this->availableWidths as $width) {
                        $resizedImageName = $this->nom . '-' . $width . 'w.' .$this->extension;
                        $filePath = storage_path('app/public/photos_edited/' .$resizedImageName);
                        if (file_exists($filePath)) {
                            $zip->addFile($filePath, basename($filePath));
                        }
                    }
                    $zip->close();
                } else {    return response()->json(['error' => 'Erreur de création de fichier zip'], 500);}
    
                // Delete the original image
                $originalImg = storage_path('app/public/photos/'.$this->nom.'.'.$this->extension);
                if (file_exists($originalImg)) {
                    unlink($originalImg);   }
    
                // Delete the resized images after creating the ZIP file
                foreach ($this->availableWidths as $width) {
                    $resizedImg = storage_path('app/public/photos_edited/' . $this->nom . '-' .$width.'w.'.$this->extension);
                    if (file_exists($resizedImg)) { unlink($resizedImg);}
                }
    
                $this->download = false;
                return response()->download($zipFilePath)->deleteFileAfterSend(true);
            } else {
                foreach ($this->availableWidths as $width) {
                    $resizedImageName = $this->nom . '-' . $width . 'w.' . $this->extension;

                    $this->download = false;
                    return response()->download(storage_path('app/public/photos_edited/' . $resizedImageName))->deleteFileAfterSend(true);
                }
            }
        }
    }
    

    public function render()
    {
        return view('livewire.landing-page');
    }
}
