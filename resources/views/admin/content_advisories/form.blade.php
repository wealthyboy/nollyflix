<div class="form-group label-floating">
    <label class="control-label" for="advisory-name">Label name <small>*</small></label>
    <input id="advisory-name" class="form-control" name="name" type="text" maxlength="100"
           value="{{ old('name', isset($advisory) ? $advisory->name : '') }}" required>
</div>
<div class="form-group label-floating">
    <label class="control-label" for="advisory-sort-order">Display order</label>
    <input id="advisory-sort-order" class="form-control" name="sort_order" type="number" min="0" max="100000"
           value="{{ old('sort_order', isset($advisory) ? $advisory->sort_order : 0) }}">
</div>
<p class="text-muted">These labels describe a title's content. They do not replace its age rating.</p>
<div class="form-footer text-right">
    <button type="submit" class="btn btn-rose btn-round">Save advisory</button>
</div>
