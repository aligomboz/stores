@csrf
<div class="form-group">
    <label for="name">Name</label>
    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
        value="{{old('name' , $categoris->name)}}">
    @error('name')
    <div class="text-danger">{{ $message }}</div>
    @enderror
</div>
<div class="form-group">
    <label for="parent">Parent</label>
    <select name="parent_id" class="custom-select custom-select-lg mb-3 @error('parent_id') is-invalid @enderror">
        <option value="">No Parent</option>
        @foreach (App\Category::get(['name','id']) as $category)

        <option @if($category->id == old('parent_id' ,$categoris->parent_id)) selected @endif value="{{$category->id}}">{{$category->name}}
        </option>

        @endforeach
    </select>
    @error('parent_id')
    <div class="text-danger">{{ $message }}</div>
    @enderror
</div>
<div class="form-group">
    <label for="status">Status</label>
    <div class="form-check">
        <input class="form-check-input" type="radio" name="status" id="status" value="published"
            @if(old('status' , $categoris->status)=='published') checked @endif>
        <label class="form-check-label" for="exampleRadios1">
            published
        </label>

    </div>
    <div class="form-check">
        <input class="form-check-input" type="radio" name="status" id="status" value="draft"
         @if(old('status' ,  $categoris->status)=='draft')checked @endif>
        <label class="form-check-label" for="exampleRadios2">
            draft
        </label>
    </div>
    @error('status')
    <div class="text-danger">{{ $message }}</div>
    @enderror
</div>
<button type="submit" class="btn btn-success">Save</button>
