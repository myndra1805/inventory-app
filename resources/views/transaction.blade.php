<x-app-layout>
  <style>
    @media screen and (max-width: 991px) {
      #container-table {
        overflow: auto;
      }
    }
  </style>

  @section('title', 'Transactions')
  <x-slot name="breadcrumbs">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item">
          <a href="/dashboard">
            <i class="mdi mdi-home"></i>
          </a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">Transactions</li>
      </ol>
    </nav>
  </x-slot>

  {{-- flash message --}}
  @if (session('message'))
  <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
    <i class="mdi mdi-check-circle me-2" style="font-size: 20px"></i>
    <div>
      {{session('message')}}
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
  @endif
  {{-- end flash message --}}

  {{-- content --}}
  <div class="card w-100 shadow border-0">
    <div class="card-body">
      <div class="container mb-3">
        <div class="row align-items-center">
          <div class="col-12 col-lg-6 px-0">
            <h3 class="card-title m-0 text-primary">Transactions Table</h3>
          </div>
          <div class="col-12 col-lg-6 px-0 text-end">
            <a href="/transactions/add" class="btn btn-primary">
              <i class="mdi mdi-plus" style="font-size: 20px"></i>
              Add Transaction
            </a>
          </div>
        </div>
      </div>
      <div id="container-table">
        <table class="table w-100 table-striped yajra-datatable nowrap">
          <thead>
            <tr>
              <th class="bg-primary text-white">No</th>
              <th class="bg-primary text-white">Code</th>
              <th class="bg-primary text-white">Status</th>
              <th class="bg-primary text-white">Created At</th>
              <th class="bg-primary text-white">Updated At</th>
              <th class="bg-primary text-white text-center"
                style="width: @role('super-admin') 240px @elserole('admin') 155px @elserole('warehouse') 100px @endrole">
                Action</th>
              <th class="bg-primary text-white"></th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>
  {{-- end content --}}


  {{-- form delete --}}
  <form action="/transactions" method="post" id="form-delete">
    @csrf
    @method('delete')
    <input type="hidden" name="id" id="id-delete">
  </form>
  {{-- end form delete --}}

  @section('scripts')
  <script>
    window.addEventListener("DOMContentLoaded", event => {
      const table = $('.yajra-datatable').DataTable({
          processing: true,
          serverSide: true,
          ajax: "/transactions/read",
          columns: [
              {data: 'DT_RowIndex', name: 'DT_RowIndex', class: 'fw-bold', width: '20px'},
              {data: 'transaction_code', name: 'transaction_code', width: '120px'},
              {data: 'status', name: 'status'},
              {data: 'created_at', name: 'created_at', width: '120px'},
              {data: 'updated_at', name: 'updated_at', width: '120px'},
              {
                  data: 'action', 
                  name: 'action', 
                  orderable: false, 
                  searchable: false,
                  width: '240px'
              },
              {
                className: 'dt-control',
                orderable: false,
                data: null,
                defaultContent: '',
                searchable: false,
                width: '20px'
              },
          ]
      });
      $('.yajra-datatable').on('click', 'td.dt-control', function () {
        const tr = $(this).closest('tr');
        const row = table.row(tr);

        if (row.child.isShown()) {
            row.child.hide();
            tr.removeClass('shown');
        } else {
            row.child(format(row.data())).show();
            tr.addClass('shown');
        }
      });
    })

    function format(d) {
      let templateProducts = `
        <tr>
          <th class="pt-4 px-2 pb-2">Name</th>
          <th class="pt-4 px-2 pb-2">Price</th>
          <th class="pt-4 px-2 pb-2">Amount</th>
          <th class="pt-4 px-2 pb-2">Total</th>
        </tr>
      `
      let total = 0
      for (let i = 0; i < d.products.length; i++) {
        const product = d.products[i];
        templateProducts += `
          <tr class="border-bottom">
            <td class="p-2">${product.name}</td>
            <td class="p-2">${formatRupiah(product.price.toString())}</td>
            <td class="p-2">${product.amount}</td>
            <td class="p-2">${formatRupiah((product.price * product.amount).toString())}</td>
          </tr>
          `
        total += product.price * product.amount
      }
      templateProducts += `
        <tr>
          <th class="p-2" colspan="3">Total Price</th>
          <th class="p-2">${formatRupiah(total.toString())}</th>
        </tr>
      `
      return /*html*/`
          <tr class="${d.supplier === '' ? 'd-none' : ''}">
            <th class="p-1">Supplier</th>
            <td class="p-1">: ${d.supplier}</td>
          </tr>
          <tr>
            <th class="p-1">Note</th>
            <td class="p-1">: ${d.note}</td>
          </tr>
          <div style="margin-top: 50px;">
            ${templateProducts}
          </div>
      `;
    }

    function destroy(id){
      window.Swal.fire({
        title: 'Confirm',
        text: 'Do you want to delete transaction ?',
        icon: 'question',
        confirmButtonText: 'Yes',
        confirmButtonColor: 'teal',
        showCancelButton: true
      }).then(response => {
        if(response.isConfirmed){
          document.querySelector("#id-delete").value = id
          document.querySelector("#form-delete").submit()
        }
      })
    }

    function formatRupiah(angka){
			var number_string = angka.replace(/[^,\d]/g, '').toString()
			const split = number_string.split(',')
			const sisa = split[0].length % 3
			let rupiah = split[0].substr(0, sisa)
			const ribuan = split[0].substr(sisa).match(/\d{3}/gi);
 
			if(ribuan){
				const separator = sisa ? '.' : '';
				rupiah += separator + ribuan.join('.');
			}
 
			rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
			return 'Rp. ' + rupiah + ',00';
		}
  </script>
  @endsection
</x-app-layout>