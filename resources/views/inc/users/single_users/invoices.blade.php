<h4>user's Invoices</h4>
                                        @if (count($user->invoices) > 0)
                                            <table class="table table-bordered" id="invoices_table">
                                                <thead>
                                                    <th>Invoice No.</th>
                                                    <th>Product / Service</th>
                                                    <th>Amount</th>
                                                    <th>Due date</th>
                                                    <th>Doctor Assigned</th>
                                                    <th>Created On</th>
                                                </thead>
                                                <tbody>
                                                    @foreach ($user->invoices as $invoice)
                                                        <tr>
                                                            <td><a href="{{route('invoices.show', $invoice->id)}}">#00HN - {{$invoice->id}}</a></td>
                                                            <td>{{$invoice->product_service}}</td>
                                                            <td>{{$invoice->amount}}</td>
                                                            <td>{{$invoice->due_date}}</td>
                                                            <td>{{App\User::findOrfail($invoice->user_id)->name}}</td>
                                                            <td>{{$invoice->created_at->diffForHumans()}}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        @else
                                            user has No invoices yet.
                                        @endif