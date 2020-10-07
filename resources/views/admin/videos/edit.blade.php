 @extends('admin.layouts.app')
@section('pagespecificstyles')
@stop
@section('content')
<div class="row">
<div class="col-sm-12">
   @include('admin.errors.errors')
   <!--      Wizard container        -->
   <div class="wizard-container">
      <div class="card wizard-card" data-color="rose" id="wizardProfile">
         <form enctype="multipart/form-data" id="product-form" action="{{ route('videos.update',['video' => $video->id ]) }}" method="post">
            @csrf
            @method('PATCH')
            <!--  You can switch " data-color="purple"   with one of the next bright colors: "green", "orange", "red", "blue"       -->
            <div class="wizard-header">
               <h3 class="wizard-title">
                  Edit Video
               </h3>
            </div>
            <div class="wizard-navigation">
               <ul>
                  <li><a href="wizard.html#Image" data-toggle="tab">Image</a></li>
                  <li><a href="wizard.html#ProductData" data-toggle="tab">Video Data</a></li>
                  <li><a href="wizard.html#RelatedProducts" data-toggle="tab">Related Videos</a></li>
               </ul>
            </div>
            <div class="tab-content">
               <div class="tab-pane" id="Image">
                  <div class="row">
                     <div  class="col-md-12  text-center">
                        <h3 class="title text-center"> Upload Video Poster Image</h3>
                        <p class="description text"> </p>
                     </div>
                     <div class="col-sm-6">
                        <div class="row">
                           <div  class="text-center">
                              <small><b>Recommended 1920 × 1080</b></small>
                           </div>
                           <div   class="col-md-12 col-sm-6 col-xs-6">
                              <div id="m_image"  class="uploadloaded_image text-center mb-3">
                                 <div class="upload-text   {{ $video->poster !== null  ?  'hide' : '' }}"> 
                                    <a class="activate-file" href="#">
                                       <img src="{{ asset('backend/img/upload_icon.png') }}">
                                       <b>Add Big poster </b> 
                                    </a>
                                 </div>
                                 <div id="remove_image" class="remove_image {{ $video->poster !== null  ?  '' : 'hide' }}">
                                    <a class="delete_image"  href="#">Remove</a>
                                 </div>
                                 @if ( $video->poster )
                                    <img id="stored_image" class="img-thumnail" src="{{ $video->poster }}" alt="">
                                 @endif

                                 <input accept="image/*"  class="upload_input" data-msg="Please add a big poster image"  type="file" id="big_poster" name="cimage"  />
                                 <input type="hidden"  value="{{ $video->poster }}"  class="file_upload_input  stored_image" id="stored_image" name="poster">
                              </div>
                           </div>

                        </div>
                     </div>
                     <div class="col-sm-6">
                        <div class="row">
                           <div   class="col-md-12 col-sm-6 col-xs-6">
                              <div id="m_image"  class="uploadloaded_image text-center mb-3">

                                 <div class="upload-text   {{ $video->tn_poster !== null  ?  'hide' : '' }}"> 
                                    <a class="activate-file" href="#">
                                       <img src="{{ asset('backend/img/upload_icon.png') }}">
                                       <b>Add small poster </b> 
                                    </a>
                                 </div>
                                 <div id="remove_image" class="remove_image {{ $video->tn_poster !== null  ?  '' : 'hide' }}">
                                    <a class="delete_image"  href="#">Remove</a>
                                 </div>

                                 @if ( $video->tn_poster )
                                    <img id="stored_image" class="img-thumnail" src="{{ $video->tn_poster }}" alt="">
                                 @endif

                                 <input accept="image/*"  class="upload_input"  data-msg="Please add a small poster image"  type="file" id="small_poster" name="caimage"  />
                                 <input type="hidden"  value="{{ $video->tn_poster }}"  class="file_upload_input  stored_image" id="stored" name="tn_poster">

                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="tab-pane" id="ProductData">
                  <h4 class="info-text">Enter Video Details</h4>
                  <div class="row">
                     <div class="col-md-8">
                        <div class="row">
                           <div class="col-md-12">
                              <div class="form-group label-floating ">
                                 <label class="control-label">Title</label>
                                 <input  required="true" name="title" data-msg="Enter video title" value="{{ $video->title ??  old('title') }}" class="form-control" type="text">
                                 <span class="material-input"></span>
                              </div>
                           </div>
                        </div>
                        <div class="row">
                           <div class="col-md-12">
                              <div class="form-group label-floating ">
                                 <label class="control-label">Price (for buying)</label>
                                 <input name="buy_price"  required="true" type="text" value="{{  $video->buy_price ??  old('buy_price') }}" class="form-control">
                                 <span class="material-input"></span>
                              </div>
                           </div>
                        </div>
                        <div class="row">
                           <div class="col-md-12">
                              <div class="form-group label-floating">
                                 <label class="control-label">Price  (for renting)</label>
                                 <input name="rent_price"  required="true" type="text" value="{{ $video->rent_price ?? old('rent_price') }}" class="form-control">
                                 <span class="material-input"></span>
                              </div>
                           </div>
                        </div>
                        <div class="row">
                           <div class="col-md-12">
                              <div class="form-group label-floating">
                                 <label class="control-label">Video Link  (Use the Http Live streaming link from vimeo.)</label>
                                 <input name="link" required="true"  data-msg="Enter video link"    type="text" value="{{ $video->link ?? old('link') }}" class="form-control">
                                 <span class="material-input"></span>
                              </div>
                           </div>
                        </div>
                        <div class="row">
                           <div class="col-md-12">
                              <div class="form-group label-floating ">
                                 <label class="control-label">Video preview link  (Do not use the Http Live streaming link for previews. Use High def 1080 (mp4, 1746x1080) or any other)</label>
                                 <input name="preview_link"  required="true"  data-msg="Enter preview link"  type="text" value="{{ $video->preview_link ?? old('preview_link') }}" class="form-control">
                                 <span class="material-input"></span>
                              </div>
                           </div>
                        </div>
                        <div class="row">
                           <div class="col-md-12">
                              <div class="form-group  is-empty no-file-input-style">
                                 <label class="control-label">Subtile file</label>
                                 <input name="track_file"  type="file" class="form-control">
                              </div>
                              <span class="material-input">{{ $video->track_file }}</span>
                           </div>
                        </div>
                        <div class="row">
                           <div class="col-md-12">
                              <div class="form-group label-floating">
                                 <label class="control-label">Film Rating (PG13, R)</label>
                                 <input name="film_rating" required="true"  data-msg="Enter video rating"   type="text" value="{{ $video->film_rating ?? old('film_rating') }}" class="form-control">
                                 <span class="material-input"></span>
                              </div>
                           </div>
                        </div>  
                        <div class="row">
                           <div class="col-md-12">
                              <div class="form-group label-floating ">
                                 <label class="control-label">Resolution (SD, HD, 4K)</label>
                                 <input name="resolution"   type="text" value="{{ $video->resolution ?? old('resolution') }}" class="form-control">
                              </div>
                           </div>
                        </div>
                        <div class="row">
                           <div class="col-md-12">
                              <div class="form-group ">
                                 <label class="control-label">Release Date  &nbsp;  {{ $video->getReversedDate() }}</label>
                                 <input name="release_date"   type="text" class="datepicker form-control" >
                                 <input name="release_date_hidden"  value="{{ $video->getReversedDate() }}"  type="hidden" class=" form-control" >
                              </div>
                           </div>
                        </div>
                        <div class="row">
                           <div class="col-md-12">
                              <div class="form-group label-floating">
                                 <label class="control-label">Duration</label>
                                 <input name="duration"   type="text" value="{{ $video->duration ?? old('duration') }}" class="form-control">
                              </div>
                           </div>
                        </div>
                        <div class="row">
                           <div class="col-md-12">
                              <div class="form-group">
                                 <label>Description</label>
                                 <div class="form-group ">
                                    <label class="control-label"> Enter description here</label>
                                    <textarea name="description" 
                                       id="description" class="form-control" rows="50">{{ $video->description ?? old('description') }}</textarea>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="row">
                           <div class="col-md-3">

                              <div class="radio">
                                 <label>
                                    <input type="radio" value="is_free" name="access_type" {{ $video->access_type ==  'is_free' ? 'checked' : '' }}>
                                    Free 
                                 </label>
                              </div>
                           </div>
                           <div class="col-md-3">
                              <div class="radio">
                                 <label>
                                    <input type="radio" value="is_only_for_buy"  name="access_type"  {{ $video->access_type ==  'is_only_for_buy' ? 'checked' : '' }} >
                                    Buy Only
                                 </label>
                              </div>
                           </div>
                           <div class="col-md-3">
                              <div class="radio">
                                 <label>
                                    <input type="radio" value="is_only_for_rent"  name="access_type"  {{ $video->access_type ==  'is_only_for_rent' ? 'checked' : '' }} >  
                                    Rent Only
                                 </label>
                              </div>
                           </div>
                           <div class="col-md-3">
                              <div class="radio">
                                 <label>
                                    <input type="radio" value="coming_soon"  name="access_type">
                                    Coming soon
                                 </label>
                              </div>
                           </div>
                           <div class="col-md-3">
                              <div class="radio">
                                 <label>
                                    <input type="radio" value="is_for_rent_and_buy" name="access_type"   {{ $video->access_type ==  'is_for_rent_and_buy' ? 'checked' : '' }}> 
                                    Buy & Rent  
                                 </label>
                              </div>
                           </div>
                        </div>
                        <div class="clearfix"></div>
                     </div>
                     <div class="col-md-4">
                        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                           <div class="panel panel-default">
                              <div class="panel-heading" role="tab" id="headingThree">
                                 <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="panels.html#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                    <h4 class="panel-title">
                                       Catgeories
                                       <i class="material-icons">keyboard_arrow_down</i>
                                    </h4>
                                 </a>
                              </div>
                              <div id="collapseThree" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingThree" aria-expanded="false">
                                 <div class="panel-body scroll">
                                    <ul>
                                       @foreach($categories as $category)
                                       <li data-caption="Documents">
                                          <div class="checkbox">
                                             <label>
                                                <input name="category_id[]" 
                                                value="{{ $category->id }}"
                                                {{ $category->checked($video->categories , $category->id) ? 'checked' : '' }} 
                                                type="checkbox">
                                                {{ $category->name }}
                                             </label>
                                          </div>
                                       </li>
                                       @endforeach
                                    </ul>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                           <div class="panel panel-default">
                              <div class="panel-heading" role="tab" id="headingThree">
                                 <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="panels.html#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                    <h4 class="panel-title ">
                                       Sections
                                       <i class="material-icons">keyboard_arrow_down</i>
                                    </h4>
                                 </a>
                              </div>
                              <div id="collapseThree" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingThree" aria-expanded="false">
                                 <div class="panel-body scroll scroll">
                                    <ul>
                                       @foreach($sections as $section)
                                       <li data-caption="Documents">
                                          <div class="checkbox">
                                             <label>
                                                <input name="section_id[]" 
                                                value="{{ $section->id }}"
                                                {{ $section->checked($video->sections , $section->id) ? 'checked' : '' }} 
                                                type="checkbox">
                                                {{ $section->name }}
                                             </label>
                                          </div>
                                       </li>
                                       @endforeach
                                    </ul>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="panel-group" id="accordionGenres" role="tablist" aria-multiselectable="true">
                           <div class="panel panel-default">
                              <div class="panel-heading" role="tab" id="heading2">
                                 <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordionGenres" href="panels.html#collapse2" aria-expanded="false" aria-controls="collapse2">
                                 <h4 class="panel-title">
                                 Videos Genres
                                 <i class="material-icons">keyboard_arrow_down</i>
                                 </h4>
                                 </a>
                              </div>
                              <div id="collapse2" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading2" aria-expanded="false">
                                 <div class="panel-body scroll">
                                    <ul>
                                       @foreach($genres as $genre)
                                          <li data-caption="Documents">
                                             <div class="checkbox">
                                                <label>
                                                   <input 
                                                         name="genre_id[]" 
                                                         value="{{ $genre->id }}" 
                                                         {{ $genre->checked($video->genres , $genre->id) ? 'checked' : '' }} 
                                                         type="checkbox"
                                                      >
                                                         {{ $genre->name }}
                                                </label>
                                             </div>
                                          </li>
                                       @endforeach
                                    </ul>
                                 </div>
                              </div>
                           </div>
                        </div>  
                        <div class="panel-group" id="accordionCasts" role="tablist" aria-multiselectable="true">
                           <div class="panel panel-default">
                              <div class="panel-heading" role="tab" id="heading4">
                                 <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordionCasts" href="panels.html#collapse3" aria-expanded="false" aria-controls="collapse3">
                                    <h4 class="panel-title">
                                    Casts
                                    <i class="material-icons">keyboard_arrow_down</i>
                                    </h4>
                                 </a>
                              </div>
                              <div id="collapse3" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingThree" aria-expanded="false">
                                 <div class="panel-body scroll">
                                    <ul>
                                       @foreach($casts as $cast)
                                          <li data-caption="Documents">
                                             <div class="checkbox">
                                                <label>
                                                   <input 
                                                      name="cast_id[]" 
                                                      value="{{ $cast->id }}" 
                                                      {{ $cast->checked($video->casts , $cast->id) ? 'checked' : '' }} 
                                                      type="checkbox">
                                                   {{ $cast->fullname() }}
                                                </label>
                                             </div>
                                          </li>
                                       @endforeach
                                    </ul>
                                 </div>
                              </div>
                           </div>
                        </div> 
                        <div class="panel-group" id="accordionFilmer" role="tablist" aria-multiselectable="true">
                           <div class="panel panel-default">
                              <div class="panel-heading" role="tab" id="heading4">
                                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordionFilmer" href="panels.html#collapseFilmer" aria-expanded="false" aria-controls="collapseFilmer">
                                       <h4 class="panel-title">
                                       Film makers
                                       <i class="material-icons">keyboard_arrow_down</i>
                                       </h4>
                                    </a>
                                 </div>
                                 <div id="collapseFilmer" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading4" aria-expanded="false">
                                    <div class="panel-body scroll">
                                       <ul>
                                          @foreach($filmers as $filmer)
                                             <li data-caption="Documents">
                                                <div class="checkbox">
                                                   <label>
                                                      <input 
                                                         name="filmer_id[]" 
                                                         value="{{ $filmer->id }}" 
                                                         {{ $filmer->checked($video->filmers , $filmer->id) ? 'checked' : '' }} 
                                                         type="checkbox">
                                                      {{ $filmer->fullname() }}
                                                   </label>
                                                </div>
                                             </li>
                                          @endforeach
                                       </ul>
                                    </div>
                              </div>
                           </div>
                        </div>  
                     </div>
                  </div>
               </div>
               <div class="tab-pane" id="RelatedProducts">
                  <h4 class="info-text">Related Videos</h4>
                  <div class="row p-attr">
                     <div class="col-md-6">
                        <div class="form-group label-floating is-empty">
                           <label class="control-label">Search</label>
                           <input name="search_videos" type="text" value="" class="search_videos form-control" >
                           <div class="search_video">
                              <table id="datatables" class="table table-striped table-shopping table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
                                  <tbody id="related_videos"></tbody>
                              </table>
                           </div>
                        </div>
                     </div>
                     <div class="col-sm-12">
                        <table id="datatables" class="table table-striped table-shopping table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
                           <thead>
                              <tr>
                                 <td></td>
                                 <td class="text-left">Video Title</td>
                                 <td class="text-left">Sort Order</td>
                                 <td class="text-left">Action</td>
                              </tr>
                           </thead>
                           <tbody class="related_videos">
                              @foreach($video->related_videos as $related_video) 
                                 <tr class="">
                                    <td class="">
                                          <div class="checkbox">
                                             <label>
                                                <input type="checkbox" checked  name="related_videos[{{  $related_video->id }}]" value="{{ $related_video->video_id }}" />
                                             </label>
                                          </div>
                                    </td>
                                    <td class="text-left p">
                                       <a class="#" href="#"> {{ $related_video->video->title }}</a>
                                    </td>
                                    <td class="text-left">
                                       <input type="number" class="d-none" name="sort_order[{{ $related_video->id }}]" value="{{  $related_video->sort_order  }}" id="" />
                                    </td>
                                    <td class="text-left"><a  class="remove_related_video"  href="/admin/delete/{{ $related_video->id }}/related_video"><i class="fa fa-trash"></i> Delete</a></td>
                                 </tr>
                              @endforeach 
                           </tbody>  
                        </table>
                     </div>
                  </div>
                  <hr/>
               </div>
               <div class="wizard-footer">
                  <div class="pull-right">
                     <input type='button' class='btn btn-next btn-fill btn-rose btn-round btn-wd' name='next' value='Next' />
                     <input type='submit' class='btn btn-finish btn-fill btn-rose   btn-round  btn-wd' name='finish' value='Finish' />
                  </div>
                  <div class="pull-left">
                     <input type='button' class='btn btn-previous btn-fill btn-primary  btn-round  btn-wd' name='previous' value='Previous' />
                  </div>
                  <div class="clearfix"></div>
               </div>
         </form>
         </div>
      </div>
      <!-- wizard container -->
   </div>
</div>
@endsection
@section('page-scripts')
<script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
<script src="{{ asset('backend/js/moment.min.js') }}"></script>
<script src="{{ asset('backend/js/bootstrap-datetimepicker.js') }}"></script>
<script src="{{ asset('backend/js/videos.js') }}"></script>
@stop

@section('inline-scripts')
$(document).ready(function() {
   CKEDITOR.replace('description',{
      height: '400px'
   }) 
   
   let activateFileExplorer = 'a.activate-file';
   let delete_image = 'a.delete_image';
   var big_poster = $("input#big_poster");
   Img.initUploadImage({
      url:'/admin/upload/image?folder=videos',
      activator: activateFileExplorer,
      inputFile: big_poster,
   });


   Img.deleteImage({
      url:'/admin/category/delete/image?folder=videos',
      activator: delete_image,
   });

   var small_poster = $("input#small_poster");
   Img.initUploadImage({
      url:'/admin/upload/image?folder=videos',
      activator: activateFileExplorer,
      inputFile: small_poster,
   });

  
});
@stop
