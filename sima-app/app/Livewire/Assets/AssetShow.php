<?php

namespace App\Livewire\Assets;

use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Models\Asset;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

#[Layout('layouts.app')]
class AssetShow extends Component
{
    public Asset $asset;

    public function mount(Asset $asset)
    {
        $this->asset = $asset->load(['category', 'location', 'vendor', 'assignedUser', 'documents', 'movements', 'maintenances']);
    }

    public function getQrCodeProperty()
    {
        return base64_encode(QrCode::format('svg')->size(200)->generate($this->asset->qr_code ?? $this->asset->code));
    }

    public function printQrPage()
    {
        return redirect()->route('assets.print-qr-page', $this->asset);
    }

    public function printQrLabels()
    {
        return redirect()->route('assets.print-qr-labels', $this->asset);
    }

    public function render()
    {
        return view('livewire.assets.asset-show');
    }
}
