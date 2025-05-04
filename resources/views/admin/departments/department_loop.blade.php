{{-- <tr class="bg-category-step{{ $department->step }}"> --}}
    <td>
        <label>
            <input class="category_check" name="bulk_ids[]" type="checkbox" value="{{ $department->id }}" />
            #{{ $department->id }}
        </label>
    </td>
    <td>
        <p class="m-0 d-flex">

            <i class="la {{ $department->icon_class }}"></i>


            <span>{!! $department->name_ar !!}</span>
            @if ($department->is_top == 1)
                <span class='badge badge-secondary mx-2' data-toggle='tooltip' title="{{ __('top') }}"
                    style="height:18px;"><img src='{{ url('uploads/images/top-menu.png') }}'
                        style="height:11px;margin-top:1px;" /></span>
            @endif
        </p>
    </td>
    <td>
        <p class="m-0 d-flex">

            <i class="la {{ $department->icon_class }}"></i>


            <span>{!! $department->name_en !!}</span>
            @if ($department->is_top == 1)
                <span class='badge badge-secondary mx-2' data-toggle='tooltip' title="{{ __('top') }}"
                    style="height:18px;"><img src='{{ url('uploads/images/top-menu.png') }}'
                        style="height:11px;margin-top:1px;" /></span>
            @endif
        </p>
    </td>

    <td>
        <img src="{{ $department->image_url }}" alt="" width="200px" height="150px" />

    </td>
    {{-- <td>
        <a href="{{ route('admin.departments.edit', $department->id) }}" class="btn btn-primary btn-sm"><i
                class="la la-pencil"></i> </a>
        <a href="{{ route('admin.department.show', $department->id) }}" class="btn btn-outline-info btn-sm"
            target="_blank"><i class="la la-eye"></i> </a>
        <a href="{{ route('admin.departments.delete', $department->id) }}" class="btn btn-danger btn-sm"
            title="@lang('general.delete')"><i class="la la-trash-o"></i> </a> --}}
            <td>
                <a href="{{ route('admin.departments.edit', $department->id) }}" class="btn btn-sm btn-primary">{{ $lang == 'ar' ? 'تعديل' : 'Edit' }}</a>
                <a href="{{ route('admin.department.show', $department->id) }}" class="btn btn-sm btn-info">{{ $lang == 'ar' ? 'تعديل' : 'Edit' }}</a>
                <form action="{{ route('admin.departments.destroy', $department->id) }}" method="POST" style="display:inline-block;" class="delete-form">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger delete-btn">{{ $lang == 'ar' ? 'حذف' : 'Delete' }}</button>
                </form>
            </td>

    {{-- </td> --}}
</tr>
