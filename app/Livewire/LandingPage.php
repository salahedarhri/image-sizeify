<?php

namespace App\Livewire;

use Livewire\Component;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Livewire\WithFileUploads;
use Intervention\Image\Encoders\JpegEncoder;

class LandingPage extends Component
{
    use WithFileUploads;
    public $photo;
    public $imageInfo = [];
    public $statut = '';
    public $widths = ['400','600','800','1200','1600'];
    public $availableWidths = [];
    public $download = false;
    public $nom = '';

    protected $rules = [
        'photo' => 'required|image|max:20400',
        'widths' => 'required|array|min:1',
    ];

    public function updatedPhoto(){
        if ($this->photo) {
            $this->imageInfo = getimagesize($this->photo->getRealPath());   }

        if ($this->imageInfo) {
            $imageWidth = $this->imageInfo[0];
            $this->availableWidths = array_filter($this->widths, function($width) use ($imageWidth) {
                return $width <= $imageWidth;   });
        }
    }

    public function scaleImage(){
        $this->validate($this->rules);
        $this->nom =  $this->photo->getClientOriginalName();
        $extension = $this->photo->getClientOriginalExtension();

        $this->photo->storeAs('photos', $this->nom, 'public'); 
        
        if($extension == 'png'){
            //Convert to .jpeg, store image and destroy gd file to free memory
            $image_gd = @imagecreatefrompng(storage_path('app/public/photos/'.$this->nom));
            imagejpeg($image_gd, storage_path('app/public/photos/'.$this->nom ));
            imagedestroy($image_gd);
        }

        try{
            $manager = new ImageManager(new Driver());
            $image = $manager->read(storage_path('app/public/photos/'.$this->nom ));
            
            if($this->availableWidths){
                foreach( $this->availableWidths as $width){
                    $resizedImage = clone $image;
                    $resizedImage->scale(width: $width);
                    $resizedImage->save(storage_path('app/public/photos_edited/'.$width.'_'.$this->nom));
                }}

            $this->statut = 'Toutes les images ont été redimensionnées avec succès !';
            $this->download = true;

        }catch(\Exception $e){
            dump( $e->getMessage());
        }
    }

    public function downloadImages(){

        if($this->availableWidths){
            if (sizeof($this->availableWidths) > 1) {
                $zip = new \ZipArchive();
                $zipFileName = 'images_' . time() . '.zip';
                $zipFilePath = storage_path('app/public/photos_edited/' . $zipFileName);
            
                if ($zip->open($zipFilePath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === TRUE) {
                    foreach ($this->availableWidths as $width) {
                        $filePath = storage_path('app/public/photos_edited/' . $width . '_' . $this->nom);
                        if (file_exists($filePath)) {
                            $zip->addFile($filePath, basename($filePath));
                        }
                    }
                    $zip->close();
                } else {
                    return response()->json(['error' => 'Erreur de création de fichier zip'], 500);
                }

                //Supprimer l'image original
                $originalImg = storage_path('app/public/photos/'.$this->nom);
                if (file_exists($originalImg)) {
                    unlink($originalImg);
                }

                //Supprimer les images après création du fichier zip
                foreach ($this->availableWidths as $width) {
                    $resizedImg = storage_path('app/public/photos_edited/' . $width . '_' . $this->nom);
                    if (file_exists($resizedImg)) {
                        unlink($resizedImg);
                    }
                }
            
                return response()->download($zipFilePath)->deleteFileAfterSend(true);
           }else{
                foreach( $this->availableWidths as $width){
                    return response()->download(
                        storage_path('app/public/photos_edited/'.$width.'_'.$this->nom));
                }
            }
        }
    }

    public function render()
    {
        return view('livewire.landing-page');
    }
}
