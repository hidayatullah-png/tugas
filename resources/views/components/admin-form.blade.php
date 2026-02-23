@props(['title', 'description' => null])

<div class="col-12 grid-margin stretch-card">
  <div class="card">
    <div class="card-body">
      <h4 class="card-title">{{ $title }}</h4>
      @if($description)
        <p class="card-description"> {{ $description }} </p>
      @endif
      
      {{-- Slot untuk isi form --}}
      {{ $slot }}
      
    </div>
  </div>
</div>