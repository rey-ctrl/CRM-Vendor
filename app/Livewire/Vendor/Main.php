<?php
namespace App\Livewire\Vendor;

use App\Models\Vendor;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth; // Tambahkan ini


class Main extends Component
{
    use WithPagination;
    
    public $search = '';
    public $filters = [
        'date_from' => '',
        'date_to' => ''
    ];
    
    public $showModal = false;
    public $vendorId = null;
    public $vendor_name;
    public $vendor_email;
    public $vendor_phone;
    public $vendor_address;
    public $notification = [
        'show' => false,
        'message' => ''
    ];

    protected $rules = [
        'vendor_name' => 'required|min:3',
        'vendor_email' => 'required|email',
        'vendor_phone' => 'required',
        'vendor_address' => 'required',
    ];

    public function openModal($vendorId = null)
    {
        $this->showModal = true;
        $this->vendorId = $vendorId;
        
        if ($vendorId) {
            $vendor = Vendor::findOrFail($vendorId);
            $this->vendor_name = $vendor->vendor_name;
            $this->vendor_email = $vendor->vendor_email;
            $this->vendor_phone = $vendor->vendor_phone;
            $this->vendor_address = $vendor->vendor_address;
        } else {
            $this->resetForm();
        }
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->vendorId = null;
        $this->vendor_name = '';
        $this->vendor_email = '';
        $this->vendor_phone = '';
        $this->vendor_address = '';
    }

    public function save()
    {
        $this->validate();

        if ($this->vendorId) {
            $vendor = Vendor::findOrFail($this->vendorId);
            $vendor->update([
                'vendor_name' => $this->vendor_name,
                'vendor_email' => $this->vendor_email,
                'vendor_phone' => $this->vendor_phone,
                'vendor_address' => $this->vendor_address,
            ]);
            $message = 'Vendor updated successfully';
        } else {
            Vendor::create([
                'user_id' => Auth::id(),
                'vendor_name' => $this->vendor_name,
                'vendor_email' => $this->vendor_email,
                'vendor_phone' => $this->vendor_phone,
                'vendor_address' => $this->vendor_address,
            ]);
            $message = 'Vendor added successfully';
        }

        $this->closeModal();
        $this->notification['show'] = true;
        $this->notification['message'] = $message;
        $this->resetPage();
    }

    public function deleteVendor($vendorId)
    {
        $vendor = Vendor::findOrFail($vendorId);
        $vendor->delete();

        $this->notification['show'] = true;
        $this->notification['message'] = 'Vendor deleted successfully.';
        $this->resetPage();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilters()
    {
        $this->resetPage();
    }

    public function render()
    {
        $vendors = Vendor::query()
            ->when($this->search, function($query) {
                $query->where(function($query) {
                    $query->where('vendor_name', 'like', "%{$this->search}%")
                        ->orWhere('vendor_email', 'like', "%{$this->search}%")
                        ->orWhere('vendor_phone', 'like', "%{$this->search}%");
                });
            })
            ->when($this->filters['date_from'], function($query) {
                $query->whereDate('created_at', '>=', $this->filters['date_from']);
            })
            ->when($this->filters['date_to'], function($query) {
                $query->whereDate('created_at', '<=', $this->filters['date_to']);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.vendor.main', [
            'vendors' => $vendors
        ]);
    }
}