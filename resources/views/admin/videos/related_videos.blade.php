@foreach($videos as $video) 
    <tr class="related_video">
        <td class="">
            <div class="checkbox">
                <label>
                    <input type="checkbox"  name="related_videos[]" value="{{  $video->id  }}" />
                    <span class="checkbox-material"><span class="check"></span></span>
                </label>
            </div>
        </td>
        <td class="text-left p">
           <a class="add_product" href="#">{{ $video->title }}</a>
        </td>
        <td class="text-left">
           <input type="number" class="hide d-none" name="sort_order[]" value="" id="" />
        </td>
        <td class="text-left"><a  class="remove_related_video"  href=""><i class="fa fa-minus"></i></a></td>
    </tr>
@endforeach 
