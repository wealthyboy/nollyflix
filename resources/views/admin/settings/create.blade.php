@extends('admin.layouts.app')
@section('pagespecificstyles')
@stop
@section('content')
<div class="row">
    <form action="{{  null !== $setting ? route('settings.update',['setting'=>$setting->id ]) : route('settings.store')  }}" method="POST"  enctype="multipart/form-data" >
        @csrf
        @if (null !== $setting )
           @method('PATCH')
        @endif
       <div class="col-md-4">
            <div class="card card-profile">
               
            <legend>Add Logo</legend>
            <div class="fileinput fileinput-new text-center" data-provides="fileinput">
                <div class="fileinput-new thumbnail">
                    <img src="{{ null !== $setting ? $setting->logo_path() : '/assets/img/image_placeholder.jpg'}}" alt="...">
                </div>
                <div class="fileinput-preview fileinput-exists thumbnail"></div>
                <div>
                    <span class="btn btn-rose btn-round btn-file">
                        <span class="fileinput-new">Select image</span>
                        <span class="fileinput-exists">Change</span>
                        <input type="file" accept="image/*" name="image" />
                    </span>
                    <a href="extended.html#pablo" class="btn btn-danger btn-round fileinput-exists" data-dismiss="fileinput"><i class="fa fa-times"></i> Remove</a>
               </div>   
            </div>
        </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-header card-header-icon" data-background-color="rose">
                    <i class="fa fa-cog fw""></i>
                </div>
                <div class="card-content">
                    <h4 class="card-title">Settings - <small class="category">Complete your website settings</small></h4>
                    <fieldset>
                        <legend>General</legend>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group label-floating">
                                    <label class="control-label">Site Name</label>
                                    <input type="text" name="site_name" value="{{ null !== $setting ? $setting->site_name : old('site_name')   }}"  id="input-name" class="form-control" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group label-floating ">
                                    <label class="control-label">Site Phone</label>
                                    <input type="text" name="site_phone" value="{{  null !== $setting ? $setting->site_phone : old('site_phone')  }}"  id="input-telephone" class="form-control" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group label-floating">
                                    <label class="control-label">Store Email Address</label>
                                    <input type="text" name="site_email" value="{{ null !== $setting  ? $setting->site_email : old('site_email')   }}" id="input-email" class="form-control" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group label-floating ">
                                    <label class="control-label">Address</label>
                                    <input class="form-control" name="address" value="{{  null !== $setting ? $setting->address : old('address')  }}" type="text">
                                </div>
                            </div>
                        </div>
                         <div class="row">
                            <div class="col-md-12">
                                <div class="form-group label-floating ">
                                    <label class="control-label">Meta title</label>
                                    <input class="form-control" name="meta_title" value="{{  null !== $setting ? $setting->meta_title : old('meta_title')  }}" type="text">
                                </div>
                            </div>
                        </div>
                         <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Meta Description</label>
                                    <div class="form-group label-floating is-empty">
                                        <label class="control-label"> </label>
                                        <textarea name="meta_description" class="form-control" rows="5">{{  null !== $setting ? $setting->meta_description :  old('meta_description') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Meta Tag Keywords </label>
                                    <div class="form-group label-floating is-empty">
                                        <label class="control-label"> </label>
                                        <textarea name="meta_tag_keywords" class="form-control" rows="5">{{  null !== $setting ? $setting->meta_tag_keywords :  old('meta_tag_keywords') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </fieldset> 
                        <fieldset>
                        <legend>Defaults</legend>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group label-floating">
                                    <label class="control-label">Default Items Per Page </label>
                                    <input type="text" name="items_per_page" value="{{ null !== $setting ? $setting->items_per_page :  old('items_per_page') }}"  id="" class="form-control" />
                                <span class="material-input"></span></div>
                            </div>
                             <div class="col-md-4">
                                <select required name="allow_multiple_logins" class="form-control">
                                <option  value="">--Allow Multiple Logins--</option>
                                    <option   {{ null !== $setting && $setting->allow_multiple_logins ? 'selected' : '' }} value="1" selected>Yes</option>
                                    <option  value="0" {{ null !== $setting && !$setting->allow_multiple_logins ? 'selected' : '' }} >No</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group label-floating">
                                    <label class="control-label">Logins per user  </label>
                                    <input type="number" name="logins" value="{{ null !== $setting ? $setting->logins :  old('logins') }}"  id="" class="form-control" />
                                    <span class="material-input"></span></div>
                                </div>
                            <div class="col-md-4">
                                <select required name="allow_multi_currency" class="form-control">
                                <option  value="">--Allow MultiCurrency--</option>
                                    <option   {{ null !== $setting && $setting->allow_multi_currency ? 'selected' : '' }} value="1" selected>Yes</option>
                                    <option  value="0" {{ null !== $setting && !$setting->allow_multi_currency ? 'selected' : '' }} >No</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <select required name="currency_id" class="form-control">
                                <option  value="">--Default Currency--</option>
                                    @foreach($currencies as $currency)
                                        @if (null !== $setting && $currency->id == $setting->currency_id)
                                            <option  value="{{ $currency->id }}" selected>{{ $currency->country }}({{  $currency->symbol }})</option>
                                        @else
                                            <option  value="{{ $currency->id }}" >{{ $currency->country }}({{  $currency->symbol }})</option>
                                        @endif
                                    @endforeach 
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group label-floating ">
                                    <label class="control-label">Alert Email </label>
                                    <input type="text" name="alert_email" value="{{  null !== $setting ? $setting->alert_email :  old('alert_email') }}" id="" class="form-control" />
                                </div>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group label-floating ">
                                    <label class="control-label">Facebook link </label>
                                    <input type="text" name="facebook_link" value="{{  null !== $setting ? $setting->facebook_link :  old('facebook_link') }}" id="input-catalog-limit" class="form-control" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group label-floating">
                                    <label class="control-label">Twitter link</label>
                                    <input type="text" name="twitter_link" value="{{ null !== $setting ? $setting->twitter_link :  old('twitter_link') }}" id="input-catalog-limit" class="form-control" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group label-floating ">
                                    <label class="control-label">Instagram Link</label>
                                    <input type="text" name="instagram_link" value="{{  null !== $setting ? $setting->instagram_link :  old('instagram_link') }}" id="input-catalog-limit" class="form-control" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group label-floating ">
                                    <label class="control-label">Youtube Link</label>
                                    <input type="text" name="youtube_link" value="{{  null !== $setting ? $setting->instagram_link :  old('instagram_link') }}" id="input-catalog-limit" class="form-control" />
                                </div>
                            </div>
                        </div>
                       
                   </fieldset> 
                    <button type="submit" class="btn btn-rose btn-round pull-right">Save</button>                      
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
@section('page-scripts')
<script src="{{ asset('backend/js/jasny-bootstrap.min.js?version='.mt_rand(1000000, 9999999) )  }}" type="text/javascript"></script>
@stop
@section('inline-scripts')    

@stop