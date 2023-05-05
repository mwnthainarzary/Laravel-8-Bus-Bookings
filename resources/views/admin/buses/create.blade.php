@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.bus.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.buses.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.bus.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', '') }}" required>
                @if($errors->has('name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.bus.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="places_available">{{ trans('cruds.bus.fields.places_available') }}</label>
                <input class="form-control {{ $errors->has('places_available') ? 'is-invalid' : '' }}" type="number" name="places_available" id="places_available" value="{{ old('places_available', '') }}" step="1" required>
                @if($errors->has('places_available'))
                    <div class="invalid-feedback">
                        {{ $errors->first('places_available') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.bus.fields.places_available_helper') }}</span>
            </div>

            {{--<div>
                <label class="required" for="name">Route</label>

                <select class="custom-select custom-select-md mb-3" name="places_available">
                <option selected>Select Route</option>
                @foreach($rides as $ride)
                 <option value="1">{{$ride->departure_place}} -to- {{$ride->arrival_place}} - {{$ride->id}}</option>
                @endforeach
               
                </select>
            </div>--}}

            <div class="form-group">
                <label class="required" for="name">Maximum Seats</label>
                <input class="form-control {{ $errors->has('maximum_seats') ? 'is-invalid' : '' }}" type="text" name="maximum_seats" id="name" value="{{ old('maximum_seats', '') }}" required>
                @if($errors->has('maximum_seats'))
                    <div class="invalid-feedback">
                        {{ $errors->first('maximum_seats') }}
                    </div>
                @endif
            </div>
            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>



@endsection
