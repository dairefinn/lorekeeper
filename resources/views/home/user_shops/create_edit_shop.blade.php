@extends('home.layout')

@section('home-title') My Shops @endsection

@section('home-content')
{!! breadcrumbs(['Home' => 'home', 'My Shops' => 'usershops', ($shop->id ? 'Edit' : 'Create').' Shop' => $shop->id ? 'usershops/edit/'.$shop->id : 'usershops/create']) !!}

<h1>{{ $shop->id ? 'Edit' : 'Create' }} Shop
    @if($shop->id)
        ({!! $shop->displayName !!})
        <a href="#" class="btn btn-danger float-right delete-shop-button">Delete Shop</a>
    @endif
</h1>

{!! Form::open(['url' => $shop->id ? 'usershops/edit/'.$shop->id : 'usershops/create', 'files' => true]) !!}

<h3>Basic Information</h3>

<div class="form-group">
    {!! Form::label('Name') !!}
    {!! Form::text('name', $shop->name, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('Shop Image (Optional)') !!} {!! add_help('This image is used on the shop index and on the shop page as a header.') !!}
    <div>{!! Form::file('image') !!}</div>
    <div class="text-muted">Recommended size: None (Choose a standard size for all shop images)</div>
    @if($shop->has_image)
        <div class="form-check">
            {!! Form::checkbox('remove_image', 1, false, ['class' => 'form-check-input']) !!}
            {!! Form::label('remove_image', 'Remove current image', ['class' => 'form-check-label']) !!}
        </div>
    @endif
</div>

<div class="form-group">
    {!! Form::label('Description (Optional)') !!}
    {!! Form::textarea('description', $shop->description, ['class' => 'form-control wysiwyg']) !!}
</div>

<div class="form-group">
    {!! Form::checkbox('is_active', 1, $shop->id ? $shop->is_active : 1, ['class' => 'form-check-input', 'data-toggle' => 'toggle']) !!}
    {!! Form::label('is_active', 'Set Active', ['class' => 'form-check-label ml-3']) !!} {!! add_help('If turned off, the shop will not be visible to regular users.') !!}
</div>

<div class="text-right">
    {!! Form::submit($shop->id ? 'Edit' : 'Create', ['class' => 'btn btn-primary']) !!}
</div>

{!! Form::close() !!}

@if($shop->id)
<h3>Shop Stock</h3> 

<div class="alert alert-warning text-center">Other users cannot buy items until the stock is set to visible. </div>
    <div id="shopStock">
        <div class="row col-12">
        @foreach($shop->stock as $stock)
        <div class="col-md-4">
            <div class="card p-3 my-1">
                <div class="row">
                    @if($stock->item->has_image)
                        <div class="col-2">
                            <img src="{{ $stock->item->imageUrl }}" style="width: 100%;" alt="{{ $stock->item->name }}">
                        </div>
                    @endif
                    <div class="col-{{ $stock->item->has_image ? '8' : '10' }}">
                        <div><a href="{{ $stock->item->idUrl }}"><strong>{{ $stock->item->name }} - {{ $stock->stock_type }}</strong></a></div>
                        <div><strong>Cost: </strong> {!! $stock->currency->display($stock->cost) !!}</div>
                    </div>
                    @if(!$stock->is_visible)<div class="col-2"> <i class="fas fa-eye-slash"></i></div>@endif
                </div> 
                <div class="text-right">
                    <button class="btn btn-primary" onclick="editStock({{$stock->id}})">
                        {{-- pencil icon --}}
                        <i class="fas fa-pencil-alt"></i>
                    </button>
                    <div class="btn btn-danger" onclick="removeShopStock({{$stock->id}})">
                        {{-- trash icon --}}
                        <i class="fas fa-trash"></i>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
@endif

@endsection

@section('scripts')
@parent
<script>
    // edit stock function
    function editStock(id) {
        loadModal("{{ url('usershops/stock/edit') }}/" + id, 'Edit Stock');
    }
    function removeShopStock(id) {
        loadModal("{{ url('usershops/stock/remove') }}/" + id, 'Remove Stock');
    }
    
    $('.delete-shop-button').on('click', function(e) {
        e.preventDefault();
        loadModal("{{ url('usershops/delete') }}/{{ $shop->id }}", 'Delete Shop');
    });
    
    
</script>
@endsection