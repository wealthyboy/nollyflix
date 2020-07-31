<label>Casts</label>
                           <div class="well well-sm" style="height: 250px; background-color: #fff; color: black; overflow: auto;">
                              <ul class="treeview" data-role="treeview">
                                 <li data-icon="" data-caption="">
                                    <ul>
                                       @foreach($casts as $cast)
                                       <li data-caption="Documents">
                                          <div class="checkbox">
                                             <label>
                                                <input name="category_id[]" value="{{ $cast->id }}
                                                   " type="checkbox">
                                                {{ $cast->fullname() }}
                                          </div>
                                       </li>
                                       @endforeach
                                    </ul>
                                 </li>
                              </ul>
                           </div>

                           <label>Directors</label>
                           <div class="well well-sm" style="height: 250px; background-color: #fff; color: black; overflow: auto;">
                              <ul class="treeview" data-role="treeview">
                                 <li data-icon="" data-caption="">
                                    <ul>
                                       @foreach($filmers as $filmer)
                                       <li data-caption="Documents">
                                          <div class="checkbox">
                                             <label>
                                                <input name="category_id[]" value="{{ $filmer->id }}
                                                   " type="checkbox">
                                                {{ $filmer->fullname() }}
                                          </div>
                                       </li>
                                       @endforeach
                                    </ul>
                                 </li>
                              </ul>
                           </div>

                           <div class="panel-group" id="accordion3" role="tablist" aria-multiselectable="true">
                              <div class="panel panel-default">
                                 <div class="panel-heading" role="tab" id="headingOne">
                                    <a role="button" data-toggle="collapse" data-parent="#accordion3" href="panels.html#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                       <h4 class="panel-title">
                                       Video Genres
                                       <i class="material-icons">keyboard_arrow_down</i>
                                       </h4>
                                    </a>
                                 </div>
                                 <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne" aria-expanded="false" style="height: 0px;">
                                    <div class="panel-body">
                                       <ul>
                                          @foreach($genres as $genre)
                                            <li data-caption="Documents">
                                             <div class="checkbox">
                                                <label>
                                                   <input name="category_id[]" value="{{ $genre->id }}
                                                      " type="checkbox">
                                                   {{ $genre->name }}
                                             </div>
                                          </li>
                                          @endforeach
                                       </ul>
                                    </div>
                                 </div>
                              </div>
                           </div>