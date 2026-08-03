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
            <form enctype="multipart/form-data" id="product-form" action="{{ route('videos.store') }}" method="post">
               @csrf
               <!--  You can switch " data-color="purple"   with one of the next bright colors: "green", "orange", "red", "blue"       -->
               <div class="wizard-header">
                  <h3 class="wizard-title">
                     Add Video
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
                        <div class="col-md-12  text-center">
                           <h3 class="title text-center"> Upload Video Poster Image</h3>
                           <p class="description text"> </p>
                        </div>
                        <div class="col-sm-6">
                           <div class="row">
                              <div class="  text-center">
                                 <small><b>Recommended 1920 × 1080</b></small>

                              </div>
                              <div class="col-md-12 col-sm-6 col-xs-6">
                                 <div id="m_image" class="uploadloaded_image text-center mb-3">
                                    <div class="upload-text">
                                       <a class="activate-file" href="#">
                                          <img src="{{ asset('backend/img/upload_icon.png') }}">
                                          <b>Add Big poster </b>
                                       </a>
                                    </div>
                                    <div id="remove_image" class="remove_image hide">
                                       <a class="delete_image" href="#">Remove</a>
                                    </div>
                                    <input accept="image/*" class="upload_input" required="true" data-msg="Please add a big poster image" type="file" id="big_poster" name="big_poster" />
                                    <input type="hidden" class="file_upload_input  stored_image" id="stored_image" name="poster">
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="col-sm-6">
                           <div class="row">
                              <div class="col-md-12 col-sm-6 col-xs-6">
                                 <div id="m_image" class="uploadloaded_image text-center mb-3">
                                    <div class="upload-text">
                                       <a class="activate-file" href="#">
                                          <img src="{{ asset('backend/img/upload_icon.png') }}">
                                          <b>Add small poster </b>
                                       </a>
                                    </div>
                                    <div id="remove_image" class="remove_image hide">
                                       <a class="delete_image" href="#">Remove</a>
                                    </div>

                                    <input accept="image/*" class="upload_input" required="true" data-msg="Please add a small poster image" type="file" id="small_poster" name="caimage" />
                                    <input type="hidden" class="file_upload_input  stored_image" id="stored_image" name="tn_poster">
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
                                 <div class="form-group label-floating is-empty">
                                    <label class="control-label">Title</label>
                                    <input required="true" name="title" data-msg="Enter video title" value="{{ old('title') }}" class="form-control" type="text">
                                    <span class="material-input"></span>
                                 </div>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-12">
                                 <label class="control-label">Content Type</label>
                                 <div class="row">
                                    <div class="col-md-3">
                                       <div class="radio">
                                          <label>
                                             <input type="radio" value="movie" name="content_type" {{ old('content_type', 'movie') == 'movie' ? 'checked' : '' }}>
                                             Movie
                                          </label>
                                       </div>
                                    </div>
                                    <div class="col-md-3">
                                       <div class="radio">
                                          <label>
                                             <input type="radio" value="series" name="content_type" {{ old('content_type') == 'series' ? 'checked' : '' }}>
                                             Series
                                          </label>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-12">
                                 <div class="form-group label-floating is-empty">
                                    <label class="control-label">Price (for buying)</label>
                                    <input name="buy_price" required="true" type="text" value="{{ old('buy_price') }}" class="form-control">
                                    <span class="material-input"></span>
                                 </div>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-12">
                                 <div class="form-group label-floating is-empty">
                                    <label class="control-label">Price (for renting)</label>
                                    <input name="rent_price" required="true" type="text" value="{{ old('rent_price') }}" class="form-control">
                                    <span class="material-input"></span>
                                 </div>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-12">
                                 <div class="form-group label-floating is-empty">
                                    <label class="control-label">USD Price (for buying)</label>
                                    <input name="buy_price_usd" type="text" value="{{ old('buy_price_usd') }}" class="form-control">
                                    <span class="material-input"></span>
                                 </div>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-12">
                                 <div class="form-group label-floating is-empty">
                                    <label class="control-label">USD Price (for renting)</label>
                                    <input name="rent_price_usd" type="text" value="{{ old('rent_price_usd') }}" class="form-control">
                                    <span class="material-input"></span>
                                 </div>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-12">
                                 <div class="form-group label-floating is-empty">
                                    <label class="control-label">Video Link (Use the Http Live streaming link from vimeo.)</label>
                                    <input name="link" data-movie-required="true" data-msg="Enter video link" type="text" value="{{ old('link') }}" class="form-control">
                                    <span class="material-input"></span>
                                 </div>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-12">
                                 <div class="form-group label-floating is-empty ">
                                    <label class="control-label">Video preview link (Do not use the Http Live streaming link for previews. Use High def 1080 (mp4, 1746x1080) or any other)</label>
                                    <input name="preview_link" required="true" data-msg="Enter preview link" type="text" value="{{ old('preview_link') }}" class="form-control">
                                    <span class="material-input"></span>
                                 </div>
                              </div>
                           </div>
                           @php
                              $oldEpisodes = old('episodes', [
                                 ['season_number' => 1, 'episode_number' => 1, 'title' => '', 'duration' => '', 'link' => '', 'preview_link' => '', 'iframe' => '', 'sort_order' => 0]
                              ]);
                           @endphp
                           <div class="series-episodes-panel" style="display:none;">
                              <h4 class="info-text">Series Episodes</h4>
                              <div class="episode-rows">
                                 @foreach($oldEpisodes as $index => $episode)
                                 <div class="panel panel-default episode-row">
                                    <div class="panel-body">
                                       <div class="row">
                                          <div class="col-md-2">
                                             <div class="form-group label-floating is-empty">
                                                <label class="control-label">Season</label>
                                                <input name="episodes[{{ $index }}][season_number]" type="number" min="1" value="{{ $episode['season_number'] ?? 1 }}" class="form-control">
                                             </div>
                                          </div>
                                          <div class="col-md-2">
                                             <div class="form-group label-floating is-empty">
                                                <label class="control-label">Episode</label>
                                                <input name="episodes[{{ $index }}][episode_number]" type="number" min="1" value="{{ $episode['episode_number'] ?? ($index + 1) }}" class="form-control">
                                             </div>
                                          </div>
                                          <div class="col-md-4">
                                             <div class="form-group label-floating is-empty">
                                                <label class="control-label">Episode title</label>
                                                <input name="episodes[{{ $index }}][title]" type="text" value="{{ $episode['title'] ?? '' }}" class="form-control">
                                             </div>
                                          </div>
                                          <div class="col-md-2">
                                             <div class="form-group label-floating is-empty">
                                                <label class="control-label">Duration</label>
                                                <input name="episodes[{{ $index }}][duration]" type="text" value="{{ $episode['duration'] ?? '' }}" class="form-control">
                                             </div>
                                          </div>
                                          <div class="col-md-2 text-right">
                                             <button type="button" class="btn btn-danger btn-simple remove-episode">Remove</button>
                                          </div>
                                       </div>
                                       <div class="row">
                                          <div class="col-md-12">
                                             <div class="form-group label-floating is-empty">
                                                <label class="control-label">Episode Vimeo HLS Link</label>
                                                <input name="episodes[{{ $index }}][link]" data-series-required="true" type="text" value="{{ $episode['link'] ?? '' }}" class="form-control episode-link-input">
                                             </div>
                                          </div>
                                       </div>
                                       <div class="row">
                                          <div class="col-md-6">
                                             <div class="form-group label-floating is-empty">
                                                <label class="control-label">Episode preview link</label>
                                                <input name="episodes[{{ $index }}][preview_link]" type="text" value="{{ $episode['preview_link'] ?? '' }}" class="form-control">
                                             </div>
                                          </div>
                                          <div class="col-md-6">
                                             <div class="form-group label-floating is-empty">
                                                <label class="control-label">Episode iframe</label>
                                                <input name="episodes[{{ $index }}][iframe]" type="text" value="{{ $episode['iframe'] ?? '' }}" class="form-control">
                                             </div>
                                          </div>
                                       </div>
                                       <input name="episodes[{{ $index }}][sort_order]" type="hidden" value="{{ $episode['sort_order'] ?? $index }}">
                                    </div>
                                 </div>
                                 @endforeach
                              </div>
                              <button type="button" class="btn btn-primary btn-simple add-episode">Add Episode</button>
                           </div>
                           <div class="row">
                              <div class="col-md-12">
                                 <div class="form-group  is-empty no-file-input-style">
                                    <label class="control-label">Subtile file</label>
                                    <input name="track_file" type="file" class="form-control">
                                 </div>
                                 <span class="material-input">Upload track file</span>

                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-12">
                                 <div class="form-group label-floating is-empty">
                                    <label class="control-label">Film Rating (PG13, R)</label>
                                    <input name="film_rating" required="true" data-msg="Enter video rating" type="text" value="{{ old('film_rating') }}" class="form-control">
                                    <span class="material-input"></span>
                                 </div>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-12">
                                 <div class="form-group label-floating is-empty">
                                    <label class="control-label">Resolution (SD, HD, 4K)</label>
                                    <input name="resolution" type="text" value="{{ old('resolution') }}" class="form-control">
                                 </div>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-12">
                                 <div class="form-group label-floating ">
                                    <label class="control-label">Release Date</label>
                                    <input name="release_date" type="text" class="datepicker form-control" value="{{ old('release_date') }}" class="form-control">
                                 </div>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-12">
                                 <div class="form-group label-floating is-empty">
                                    <label class="control-label">Duration</label>
                                    <input name="duration" type="text" value="{{ old('duration') }}" class="form-control">
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
                                          class="form-control" rows="50">{{ old('description') }}</textarea>
                                    </div>
                                 </div>
                              </div>
                           </div>

                           <div class="row">
                              <div class="col-md-3">

                                 <div class="radio">
                                    <label>
                                       <input type="radio" value="is_free" name="access_type">
                                       Free
                                    </label>
                                 </div>
                              </div>
                              <div class="col-md-3">
                                 <div class="radio">
                                    <label>
                                       <input type="radio" value="is_only_for_buy" name="access_type">
                                       Buy Only
                                    </label>
                                 </div>
                              </div>
                              <div class="col-md-3">
                                 <div class="radio">
                                    <label>
                                       <input type="radio" value="coming_soon" name="access_type">
                                       Coming soon
                                    </label>
                                 </div>
                              </div>
                              <div class="col-md-3">
                                 <div class="radio">
                                    <label>
                                       <input type="radio" value="is_only_for_rent" name="access_type">
                                       Rent Only
                                    </label>
                                 </div>
                              </div>
                              <div class="col-md-3">
                                 <div class="radio">
                                    <label>
                                       <input type="radio" value="is_for_rent_and_buy" name="access_type" checked="true">
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
                                                   <input name="category_id[]" value="{{ $category->id }}" type="checkbox">
                                                   {{ $category->name }}
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
                                          Videos Genres <button type="button" class="btn btn-rose btn-simple btn-xs pull-right quick-add-metadata" data-type="genre"><i class="material-icons">add_circle</i> Add</button>
                                          <i class="material-icons">keyboard_arrow_down</i>
                                       </h4>
                                    </a>
                                 </div>
                                 <div id="collapse2" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading2" aria-expanded="false">
                                    <div class="panel-body scroll">
                                       <ul data-metadata-list="genre">
                                          @foreach($genres as $genre)
                                          <li data-caption="Documents">
                                             <div class="checkbox">
                                                <label>
                                                   <input name="genre_id[]" value="{{ $genre->id }}" type="checkbox">
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
                           <div class="panel-group" id="accordionGenres" role="tablist" aria-multiselectable="true">
                              <div class="panel panel-default">
                                 <div class="panel-heading" role="tab" id="heading2">
                                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordionGenres" href="panels.html#collapse2" aria-expanded="false" aria-controls="collapse2">
                                       <h4 class="panel-title">
                                          Videos Sections <button type="button" class="btn btn-rose btn-simple btn-xs pull-right quick-add-metadata" data-type="section"><i class="material-icons">add_circle</i> Add</button>
                                          <i class="material-icons">keyboard_arrow_down</i>
                                       </h4>
                                    </a>
                                 </div>
                                 <div id="collapse2" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading2" aria-expanded="false">
                                    <div class="panel-body scroll">
                                       <ul data-metadata-list="section">
                                          @foreach($sections as $section)
                                          <li data-caption="Documents">
                                             <div class="checkbox">
                                                <label>
                                                   <input name="section_id[]" value="{{ $section->id }}" type="checkbox">
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
                                          Excludes
                                          <i class="material-icons">keyboard_arrow_down</i>
                                       </h4>
                                    </a>
                                 </div>
                                 <div id="collapse2" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading2" aria-expanded="false">
                                    <div class="panel-body scroll">
                                       <ul>
                                          @foreach($excludes as $key => $exclude)
                                          <li data-caption="Documents">
                                             <div class="checkbox">
                                                <label>
                                                   <input name="excludes[]" value="{{ $key  }}" type="checkbox">
                                                   {{ $key }}
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
                                          Casts <button type="button" class="btn btn-rose btn-simple btn-xs pull-right quick-add-metadata" data-type="cast"><i class="material-icons">add_circle</i> Add</button>
                                          <i class="material-icons">keyboard_arrow_down</i>
                                       </h4>
                                    </a>
                                 </div>
                                 <div id="collapse3" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingThree" aria-expanded="false">
                                    <div class="panel-body scroll">
                                       <ul data-metadata-list="cast">
                                          @foreach($casts as $cast)
                                          <li data-caption="Documents">
                                             <div class="checkbox">
                                                <label>
                                                   <input name="cast_id[]" value="{{ $cast->id }}" type="checkbox">
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
                                          Film makers <button type="button" class="btn btn-rose btn-simple btn-xs pull-right quick-add-metadata" data-type="filmer"><i class="material-icons">add_circle</i> Add</button>
                                          <i class="material-icons">keyboard_arrow_down</i>
                                       </h4>
                                    </a>
                                 </div>
                                 <div id="collapseFilmer" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading4" aria-expanded="false">
                                    <div class="panel-body scroll">
                                       <ul data-metadata-list="filmer">
                                          @foreach($filmers as $filmer)
                                          <li data-caption="Documents">
                                             <div class="checkbox">
                                                <label>
                                                   <input name="filmer_id[]" value="{{ $filmer->id }}" type="checkbox">
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
                              <input name="search_videos" type="text" value="" class="search_videos form-control">
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
                              <tbody class="related_videos"></tbody>
                           </table>
                        </div>
                     </div>
                     <hr />
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
<div class="modal fade" id="quickMetadataModal" tabindex="-1" role="dialog" aria-labelledby="quickMetadataTitle">
   <div class="modal-dialog" role="document"><div class="modal-content">
      <div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title" id="quickMetadataTitle">Add item</h4></div>
      <div class="modal-body">
         <div class="alert alert-danger quick-metadata-error" style="display:none"></div>
         <input type="hidden" id="quickMetadataType">
         <div class="form-group"><label for="quickMetadataName" class="control-label">Name</label><input type="text" id="quickMetadataName" class="form-control" maxlength="100" autocomplete="off"></div>
         <div class="form-group quick-metadata-last-name" style="display:none"><label for="quickMetadataLastName" class="control-label">Last name</label><input type="text" id="quickMetadataLastName" class="form-control" maxlength="100" autocomplete="off"></div>
      </div>
      <div class="modal-footer"><button type="button" class="btn btn-default btn-round" data-dismiss="modal">Cancel</button><button type="button" class="btn btn-rose btn-round" id="saveQuickMetadata"><span class="quick-save-label">Add and select</span><i class="fa fa-spinner fa-spin quick-save-spinner" style="display:none"></i></button></div>
   </div></div>
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

var quickMetadataNames = { genre: 'genre', section: 'section', cast: 'cast member', filmer: 'film maker' };

$(document).on('click', '.quick-add-metadata', function(event) {
event.preventDefault();
event.stopPropagation();
var type = $(this).data('type');
$('#quickMetadataType').val(type);
$('#quickMetadataTitle').text('Add ' + quickMetadataNames[type]);
$('#quickMetadataName').val('');
$('#quickMetadataLastName').val('');
$('.quick-metadata-last-name').toggle(type === 'cast' || type === 'filmer');
$('.quick-metadata-error').hide().empty();
$('#quickMetadataModal').modal('show');
setTimeout(function() { $('#quickMetadataName').focus(); }, 300);
});

$('#saveQuickMetadata').on('click', function() {
var button = $(this);
var type = $('#quickMetadataType').val();
button.prop('disabled', true);
button.find('.quick-save-label').text('Adding...');
button.find('.quick-save-spinner').show();
$('.quick-metadata-error').hide().empty();

$.ajax({
url: '{{ route('videos.metadata.store') }}',
method: 'POST',
headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
data: { type: type, name: $('#quickMetadataName').val(), last_name: $('#quickMetadataLastName').val() }
}).done(function(response) {
var inputNames = { genre: 'genre_id[]', section: 'section_id[]', cast: 'cast_id[]', filmer: 'filmer_id[]' };
var item = $('<li data-caption="Documents"><div class="checkbox"><label><input type="checkbox" checked> <span></span></label></div></li>');
item.find('input').attr('name', inputNames[type]).val(response.item.id);
item.find('span').text(response.item.label);
$('[data-metadata-list="' + type + '"]').prepend(item);
if ($.material && $.material.checkbox) {
$.material.checkbox(item.find('input[type="checkbox"]'));
}
$('#quickMetadataModal').modal('hide');
}).fail(function(xhr) {
var errors = xhr.responseJSON && xhr.responseJSON.errors ? xhr.responseJSON.errors : null;
var message = errors ? Object.keys(errors).map(function(key) { return errors[key][0]; }).join('<br>') : 'The item could not be created. Please try again.';
$('.quick-metadata-error').html(message).show();
}).always(function() {
button.prop('disabled', false);
button.find('.quick-save-label').text('Add and select');
button.find('.quick-save-spinner').hide();
});
});

$('#quickMetadataName, #quickMetadataLastName').on('keydown', function(event) {
if (event.keyCode === 13) { event.preventDefault(); $('#saveQuickMetadata').click(); }
});

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

function episodeRowTemplate(index) {
return '<div class="panel panel-default episode-row">' +
'<div class="panel-body">' +
'<div class="row">' +
'<div class="col-md-2"><div class="form-group label-floating is-empty"><label class="control-label">Season</label><input name="episodes[' + index + '][season_number]" type="number" min="1" value="1" class="form-control"></div></div>' +
'<div class="col-md-2"><div class="form-group label-floating is-empty"><label class="control-label">Episode</label><input name="episodes[' + index + '][episode_number]" type="number" min="1" value="' + (index + 1) + '" class="form-control"></div></div>' +
'<div class="col-md-4"><div class="form-group label-floating is-empty"><label class="control-label">Episode title</label><input name="episodes[' + index + '][title]" type="text" class="form-control"></div></div>' +
'<div class="col-md-2"><div class="form-group label-floating is-empty"><label class="control-label">Duration</label><input name="episodes[' + index + '][duration]" type="text" class="form-control"></div></div>' +
'<div class="col-md-2 text-right"><button type="button" class="btn btn-danger btn-simple remove-episode">Remove</button></div>' +
'</div>' +
'<div class="row"><div class="col-md-12"><div class="form-group label-floating is-empty"><label class="control-label">Episode Vimeo HLS Link</label><input name="episodes[' + index + '][link]" data-series-required="true" type="text" class="form-control episode-link-input"></div></div></div>' +
'<div class="row"><div class="col-md-6"><div class="form-group label-floating is-empty"><label class="control-label">Episode preview link</label><input name="episodes[' + index + '][preview_link]" type="text" class="form-control"></div></div><div class="col-md-6"><div class="form-group label-floating is-empty"><label class="control-label">Episode iframe</label><input name="episodes[' + index + '][iframe]" type="text" class="form-control"></div></div></div>' +
'<input name="episodes[' + index + '][sort_order]" type="hidden" value="' + index + '">' +
'</div>' +
'</div>';
}

function toggleSeriesFields() {
var contentType = $('input[name="content_type"]:checked').val();
var isSeries = contentType === 'series';
$('.series-episodes-panel').toggle(isSeries);
$('input[data-movie-required="true"]').prop('required', !isSeries).closest('.row').toggle(!isSeries);
$('input[data-series-required="true"]').prop('required', isSeries);
}

$('input[name="content_type"]').on('change', toggleSeriesFields);
$('.add-episode').on('click', function() {
var index = $('.episode-row').length;
$('.episode-rows').append(episodeRowTemplate(index));
toggleSeriesFields();
});
$(document).on('click', '.remove-episode', function() {
if ($('.episode-row').length > 1) {
$(this).closest('.episode-row').remove();
}
});
toggleSeriesFields();

var small_poster = $("input#small_poster");

Img.initUploadImage({
url:'/admin/upload/image?folder=videos',
activator: activateFileExplorer,
inputFile: small_poster,
});

});
@stop
