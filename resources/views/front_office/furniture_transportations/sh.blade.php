
                           <label for="name" class="mb-1 mt-2">{{ $lang == 'ar' ? 'المدينة' : 'City' }} : </label>

                           <select name="from_city" class="form-control js-select2-custom">
                            <option value="">{{ __('اختر المدينة') }}</option>
                            @foreach ($cities as $city)
                                <option value="{{ $city->id }}">
                                    {{ $lang == 'ar' ?  $city->name_ar :$city->name_en  }}
                                </option>
                            @endforeach
                             </select>



   <select name="to_city" class="form-control js-select2-custom">
                            <option value="">{{ __('اختر المدينة') }}</option>
                            @foreach ($cities as $city)
                                <option value="{{ $city->id }}">
                                    {{ $lang == 'ar' ?  $city->name_ar :$city->name_en  }}
                                </option>
                            @endforeach
   </select>

    @if (auth()->user()->governement == $service->from_city)

    {{$lang == 'ar'? $form_city->name_ar:$form_city->name_en}}
    {{$lang == 'ar'? $to_city->name_ar:$to_city->name_en}}


                   <div class="form-group">
                        <label for="" class="mb-1">{{ $lang == 'ar' ? 'الموقع' : 'Location' }}
                            :</label>
                        @if (isset($service->form_city))
                            <p>{{$lang == 'ar'? $form_city->name_ar:$form_city->name_en}}</p>
                        @endif
                    </div>
