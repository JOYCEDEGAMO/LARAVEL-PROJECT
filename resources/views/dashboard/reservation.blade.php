@extends('master')
@section('title', 'Dashboard')
    

@section('content')
@include('partials._navbar')


<div class="container-fluid">
    <header class="fs-1 text-center">MY RESERVATIONS</header>

    <table class="table table-striped table-hover table-bordered mt-4">
        <thead class="table-primary">
            <tr>
                <th scope="col">#</th>
                <th scope="col">Guest House</th>
                <th scope="col">Guest Name</th>
                <th scope="col">Guest Address</th>
                <th scope="col">Contact No.</th>
                <th scope="col">Payment Process</th>
                <th scope="col">Guest House Address</th>
                <th scope="col">Date</th>
                <th scope="col">Status</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody id="tbl">   


            @if(count($reservations) === 0)
            <tr>
                <td colspan="10" class="text-center"> <h3>No reservations found</h3> </td>
            </tr>
            @endif

           
            @foreach($reservations as $reservation)

            @php

            $created_time = $reservation->created_at;
            $expire_time = $created_time->copy()->addHours(24);
            $is_expired = $created_time->greaterThan($expire_time);

            $status = '';
            switch($reservation->status){
                case 'approved':
                    $status .= '<span class="rounded" style="background-color: hsl(145, 63%, 40%);">'.  $reservation->status   .'</span>';
                    break;
                case 'pending':
                    $status .= '<span class="rounded bg-warning">'.  $reservation->status   .'</span>';
                    break;
                case 'cancelled':
                    $status .= '<span class="rounded bg-danger">'.  $reservation->status  .'</span>';
                    break;
            }

            @endphp

            <tr id="reservation{{ $reservation->id }}">
                <td scope="row"> {{ $reservation->id }} </td>
                <td> {{ $reservation->guest_house->room_name }} </td>
                <td> {{ $reservation->name }} </td>
                <td> {{ $reservation->address }} </td>
                <td> {{ $reservation->contact_no }} </td>
                <td> {{ $reservation->payment_process }} </td>
                <td> {{ $reservation->guest_house->room_location }} </td>
                <td> {{ $reservation->created_at->format('d/m/Y') }} </td>
                <td> <span class="rounded bg-danger"> {!! $status !!}  </span> </td>
                <td>
                    <button class="btn btn-warning btn-sm" {{ $is_expired ? "disabled" : "" }} data-bs-toggle="modal" data-bs-target="#cancel_reservation_modal{{ $reservation->id }}"><i class="bi bi-x-circle-fill h5 text-dark"></i></button>
                    <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#delete_reservation_modal{{ $reservation->id }}"><i class="bi bi-trash-fill h5 text-dark"></i></button>
                </td>
              </tr>

              <div class="modal fade" id="delete_reservation_modal{{ $reservation->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h1 class="modal-title fs-5" id="exampleModalLabel">Delete Reservation?</h1>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <h4> {{ $reservation->guest_house->room_name }} </h4>
                        <h5> Location: {{ $reservation->guest_house->room_location }} </h5>
                        <h5> Date: {{ $reservation->created_at }} </h5>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                      <form action="reservation/delete" method="POST">
                        @method('DELETE')
                        @csrf
                            <button class="btn btn-danger" id="btn-delete" onclick="deleteReservation({{ $reservation->id }})" data-bs-dismiss="modal"> Delete </button>
                    </form>
                    </div>
                  </div>
                </div>
              </div>

              <div class="modal fade" id="cancel_reservation_modal{{ $reservation->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h1 class="modal-title fs-5" id="exampleModalLabel">Cancel Reservation?</h1>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <h4> {{ $reservation->guest_house->room_name }} </h4>
                        <h5> Location: {{ $reservation->guest_house->room_location }} </h5>
                        <h5> Date: {{ $reservation->created_at }} </h5>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                      <form action="reservation/delete" method="POST">
                        @method('DELETE')
                        @csrf
                            <button class="btn btn-warning" id="btn-cancel" onclick="cancelReservation({{ $reservation->id }})" data-bs-dismiss="modal"> Cancel </button>
                    </form>
                    </div>
                  </div>
                </div>
              </div>

            @endforeach

        </tbody>
    </table>
</div>

<div class="" id="modalDiv"></div>



@include('partials._footer')

<script>

        function cancelReservation(id) {
            event.preventDefault();
            $.ajax({
                type: 'POST',
                url: 'reservation/cancel',
                data: {
                    _method: 'UPDATE',
                    _token: ' {{ csrf_token() }} ',
                    id: id
                },
                success: function(data) {
                    console.log(data)
                },
                error: function(error) {
                    console.log(error)
                }
            })
        }

        function deleteReservation(id) {
            event.preventDefault();
            $.ajax({
                type: 'POST',
                url: 'reservation/delete',
                data: {
                    _method: 'DELETE',
                    _token: '{{ csrf_token() }}',
                    id: id
                },
                success: function(data) {
                    console.log(data)
                    console.log($('#reservation'+id))
                    $('#reservation'+id).remove()
                },
                error: function(error) {
                    console.log(error)
                }
            })
        }
</script>

@endsection