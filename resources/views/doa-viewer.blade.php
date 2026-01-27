@extends('layouts.doa-viewer')

@section('content')

<main class="main">

    <br>
    <br>

    <br>
    <br>
  
    <!-- PDF Viewer Section -->
    <section class="pdf-viewer-section" style="padding: 0; background: #f8f9fa;">
        <div class="container-fluid p-0">
            <div class="pdf-viewer-wrapper" style="position: relative; width: 100%; height: calc(100vh - 200px); min-height: 600px;">
                <iframe 
                    src="{{ $doa->doa_url }}" 
                    style="width: 100%; height: 100%; border: none; display: block;"
                    title="{{ $doa->judul }}">
                </iframe>
            </div>
        </div>
    </section>

</main>

@endsection

@push('styles')
<style>
    /* Remove default padding from main */
    .pdf-viewer-section {
        margin-bottom: 0;
    }
    
    /* Make sure iframe takes full available space */
    .pdf-viewer-wrapper {
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .pdf-viewer-wrapper {
            height: calc(100vh - 250px) !important;
            min-height: 500px;
        }
        
        .page-title {
            padding: 20px 0 !important;
        }
        
        .page-title h3 {
            font-size: 1.2rem;
        }
    }
</style>
@endpush