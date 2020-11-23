@csrf

<div class="form-group">
    <label for="name">Name</label>
    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
        value="{{old('name' , $products->name)}}">
    @error('name')
    <div class="text-danger">{{ $message }}</div>
    @enderror
</div>
<div class="form-group">
    <label for="parent">Category</label>
    <select name="category_id" class="custom-select custom-select-lg mb-3 @error('category_id') is-invalid @enderror">
        <option value="">Select Category</option>
        @foreach (App\Category::all() as $category)
        <option @if($category->id == old('category_id' , $products->category_id)) selected @endif
            value="{{$category->id}}">{{$category->name}}
        </option>

        @endforeach
    </select>
    @error('parent_id')
    <div class="text-danger">{{ $message }}</div>
    @enderror
</div>
<div class="form-group">
    <label for="price">Price</label>
    <input type="text" class="form-control @error('price') is-invalid @enderror" id="price" name="price"
        value="{{old('price' , $products->price)}}">
    @error('price')
    <div class="text-danger">{{ $message }}</div>
    @enderror
</div>
<div class="form-group">
    <label for="description">Description</label>
    <textarea rows="5" class="form-control @error('description') is-invalid @enderror" id="description" name="description">{{old('description' , $products->description)}}</textarea>
    @error('description')
    <div class="text-danger">{{ $message }}</div>
    @enderror
</div>
<div class="form-group">
    <label for="img">Imag</label>
    <div class="d-flex">
        <input type="file" class="form-control  @error('img') is-invalid @enderror" id="img" name="img">

        @if ($products->img)
        <img src="{{asset('/images/products/'.$products->img)}}" height="120" alt="" class="ml-auto">
        @endif
    </div>
    @error('img')
    <div class="text-danger">{{ $message }}</div>
    @enderror
</div>
<div class="form-group">
    <label for="img">Gallery</label>
    <div class="d-flex">
        <input type="file" class="form-control  @error('gallery') is-invalid @enderror" id="gallery" name="gallery[]"
            multiple>
    </div>
    <div class="d-flex">
        @foreach ($gallery as $gal)
        <div> <a href="{{route('products.show',[$gal->id])}}">delete</a>
            <img class="ml-2 rounded border p-1" src="{{asset('/images/products/' .$gal->imgPath)}}" height="70" alt="">
        </div>
        @endforeach
    </div>
    @error('gallery')
    <div class="text-danger">{{ $message }}</div>
    @enderror
</div>
<div class="form-group">
    <label for="tags">Tags</label>
    <input type="text" class="form-control @error('tags') is-invalid @enderror" id="tags" name="tags"
        value="{{old('tags' , implode(',' ,$products->tags->pluck('name')->toArray()))}}">
    @error('tags')
    <div class="text-danger">{{ $message }}</div>
    @enderror
</div>
<button type="submit" class="btn btn-success">Save</button>
@push('script')
<script src="{{asset('js/trumbowyg/trumbowyg.min.js')}}"></script>
<script>
    $('#description').trumbowyg();
</script>
@endpush
@push('style')
<link rel="stylesheet" href="{{asset('js/trumbowyg/ui/trumbowyg.min.css')}}">
@endpush