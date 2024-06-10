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
    public $nom = '';
    public $extension = '';

    protected $rules = [
        'photos.*' => 'required|image|max:20400',
        'widths' => 'required|array|min:1',
    ];

    public function updatedPhotos(){
        if ($this->photos){
            foreach($this->photos as $index=>$photo){
                $this->imageInfos[] = getimagesize($photo->getRealPath());
                $this->availableWidths[$index] = []; 
                $this->selectedWidths[$index] = [];
                
                $imageWidth = $this->imageInfos[$index][0];
                $this->availableWidths[$index] = array_filter($this->allWidths, function($width) use ($imageWidth) {
                    return $width <= $imageWidth;
                });
            }

            // dd($this->availableWidths, $this->photos);
        }
    }

    public function scaleImage(){

        dd($this->selectedWidths, $this->availableWidths );

        // $this->validate($this->rules);
        // $this->nom = pathinfo($this->photo->getClientOriginalName(), PATHINFO_FILENAME);
        // $this->extension = $this->photo->getClientOriginalExtension();
    
        // $this->photo->storeAs('photos', $this->nom . '.' . $this->extension, 'public'); 
    
        // if ($this->extension == 'png') {
        //     // Convert to .jpeg, store image, and destroy gd file to free memory
        //     $image_gd = @imagecreatefrompng(storage_path('app/public/photos/' . $this->nom . '.' . $this->extension));
        //     imagejpeg($image_gd, storage_path('app/public/photos/' . $this->nom . '.jpg'));
        //     imagedestroy($image_gd);
        //     $this->extension = 'jpg';
        // }
    
        // try {
        //     $manager = new ImageManager(new Driver());
        //     $image = $manager->read(storage_path('app/public/photos/' . $this->nom . '.' . $this->extension));
            
        //     if ($this->availableWidths) {
        //         foreach ($this->availableWidths as $width) {
        //             $resizedImage = clone $image;
        //             $resizedImage->scale(width: $width);
        //             $resizedImageName = $this->nom . '-' . $width . 'w.' . $this->extension;
        //             $resizedImage->save(storage_path('app/public/photos_edited/' . $resizedImageName));
        //         }}
    
        //     $this->statut = 'Toutes les images ont été redimensionnées avec succès !';
        //     $this->download = true;
        // } catch (\Exception $e) {   dump($e->getMessage()); }
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
