@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('base.announcement_offer_for_transport') }}</div>
                <div class="card-body">
                    @php $iterator = 0; @endphp
                    @foreach ( $announcements as $announcement )
                        <table class="table table-sm table-light">
                            <thead>
                              <tr class="table-active">
                                <th colspan="2" scope="col">{{ $announcementTitles[ $iterator++ ] }}</th>

                              </tr>
                            </thead>
                            <tbody>

                              <tr>
                                <th scope="row">&nbsp&nbsp</th>
                                <td class="d-flex align-items-center">
                                    <div class="text-start">
                                        <form class="d-inline-block me-2" action="{{ route('courier_announcement.show', ['courier_announcement' => $announcement->id ] ) }}" method="GET">
                                            <button type="submit" class="btn btn-primary">{{ __( 'base.details_announcement_button' ) }}</button>
                                        </form>
                                        @if ( Auth::user()->id == $announcement->author )
                                            <form class="d-inline-block me-2">
                                                @csrf
                                                <button type="submit" class="btn btn-success">{{ __( 'base.edit_announcement_button' ) }}</button>
                                            </form>
                                            <form class="d-inline-block me-2">
                                                @csrf
                                                <button type="submit" class="btn btn-danger">{{ __( 'base.delete_announcement_button' ) }}</button>
                                            </form>
                                            @if ( $announcement->priority === null )
                                                <form class="d-inline-block">
                                                    @csrf
                                                    <button type="submit" class="btn btn-warning">{{ __( 'base.upgrade_announcement_button' ) }}</button>
                                                </form>
                                            @endif

                                        @endif
                                    </div>
                                    @php $announcementExperierience = $announcement->experience_date !== null ? $announcement->experience_date : __( 'base.courier_announcement_no_experience_date' ) @endphp
                                    <div class="small text-end ms-auto">{{ __( 'base.date_look_for_announcement' ) . $announcement->created_at . " | " . __( 'base.date_look_for_announcement_experience' ) . $announcementExperierience }}</div>
                                </td>

                              </tr>
                            </tbody>
                          </table>
                    @endforeach
                    {{ $announcements->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
