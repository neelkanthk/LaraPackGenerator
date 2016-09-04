<form method="POST" role="form" action="{{ route('rt_generate') }}">
    <input name="_token" type="hidden" value="{{ csrf_token() }}"/>
    <div class="form-group col-sm-12">  
        @foreach($fields as $field)
        <div class="col-sm-6">
            <input {{ in_array($field, $required) ? "required=required" : "" }} name="{{$field}}" placeholder="Package {{$field}} ({{ in_array($field, $required) ? "required" : "optional" }})" type="text" class="{{ ($field == 'description') ? 'form-control' : 'form-control no_special_chars' }}" id="{{$field}}">
            {{ $errors->first($field) }}
            <br>
        </div>
        @endforeach
    </div>
    <button type="submit" class="btn btn-danger">Generate</button>
</form>