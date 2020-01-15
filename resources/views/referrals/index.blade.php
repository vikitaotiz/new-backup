@extends('adminlte::page')

@section('content')
   
    <div class="panel panel-success">
                <div class="panel-heading">
                      <div class="row">
                        <div class="col-md-4">
                            <button onclick="goBack()" class="btn btn-sm btn-success"><i class="fa fa-arrow-left"></i> Back</button>
                        </div>
                        <div class="col-md-4">
                            <strong>All Referral Letters</strong>
                        </div>
                        <div class="col-md-4">
                            <a href="{{route('referrals.create')}}" class="btn btn-sm btn-primary btn-block"><i class="fa fa-plus"></i> Create New referral</a>
                        </div>
                    </div>
                </div>

                <div class="panel-body">

                        @if(count($referrals) > 0)
                        <table class="table table-bordered" id="referrals_table">
                            <thead>
                                <tr>
                                    <th>Ptient Name</th>
                                    <th>Created On</th>
                                    <th>Created By</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                                    @foreach ($referrals as $referral)
                                        <tr>
                                        <td>
                                            {{$referral->user->firstname}}
                                            {{$referral->user->lastname}}
                                        </td>
                                        <td>{{$referral->created_at->format('D, jS, M, Y')}}</td>
                                        <td>{{App\User::find($referral->creator_id)->firstname}}</td>
                                        <td>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <a href="{{route('referrals.show', $referral->id)}}" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> View</a>
                                                </div>
                                                <div class="col-md-8">
                                                    <form action="{{route('referrals.destroy', $referral->id)}}" method="POST">
                                                        {{csrf_field()}}{{method_field('DELETE')}}
                                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')"><i class="fa fa-trash"></i> Delete</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </td>
                                        </tr>
                                    @endforeach
                    
                            </tbody>
                        </table>
                    @else
                        <h4>There are no referrals</h4>
                    @endif
                </div>
            </div>
    
@endsection
