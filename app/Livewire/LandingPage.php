<?php

namespace App\Livewire;

use Livewire\Component;
use Intervention\Image\ImageManager;
use \Intervention\Image\Drivers\Gd\Driver;
use Livewire\WithFileUploads;

class LandingPage extends Component
{
    use WithFileUploads;
    public $photo;
    public $imageInfo = [];
    public $statut = '';
    public $widths = ['400','600','800','1200','1600'];
    public $availableWidths = [];

    protected $rules = [
        'photo' => 'required|image|max:20400',
        'widths' => 'required|array|min:1',
    ];

    public function updatedPhoto(){
        if ($this->photo) {
            $this->imageInfo = getimagesize($this->photo->getRealPath());
        }

        if ($this->imageInfo) {
            $imageWidth = $this->imageInfo[0];

            // Filter available widths
            $this->availableWidths = array_filter($this->widths, function($width) use ($imageWidth) {
                return $width <= $imageWidth;   });
        }
    }

    public function scaleImage(){
        $this->validate($this->rules);

        $nom =  $this->photo->getClientOriginalName();
        $this->photo->storeAs('photos', $nom, 'public');

        try{
            $manager = new ImageManager(new Driver());
            $image = $manager->read(storage_path('app/public/photos/'.$nom ));
            $image->scale(height: 50);
            $image->save(storage_path('app/public/photos_edited/'.$nom ));

        }catch(\Exception $e){
            dump( $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.landing-page');
    }
}
