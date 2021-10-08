@section('header')
	<script type="text/x-mathjax-config">
		MathJax.Hub.Config({
			extensions: ["tex2jax.js"],
			tex2jax: {inlineMath: [["$","$"], ["\\(","\\)"]]},
		});
	</script>
	<script type="text/javascript" src="{{ asset('js/mathjax/tex-chtml.js') }}"></script>
	{{-- <script type="text/javascript" async
		src="https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.7/MathJax.js?config=TeX-AMS_CHTML"></script> --}}
	<style>
		div.scrollable {
			max-height: 10em;
			margin: 0;
			padding: 0;
			overflow: auto;
		}
		.order-header {
			width: 3%;
		}
		.action-header {
			width: 10%;
		}
		.content-header {
			width: 82%
		}
		.grade-header {
			width: 5%;
		}
		.select-header-modal {
			width: 14%;
		}
		.question-header-modal {
			width: 81%;
		}
		.grade-header-modal {
			width: 5%
		}
		.modal-dialog{
			overflow-y: initial !important
		}
		.modal-body{
			height: 80vh;
			overflow-y: auto;
		}
		strong {
			font-weight: normal;
		}
	</style>
@endsection

<form method="post" action="{{ $action == 'create' ? route('teacher.test.store', [], false) : route('teacher.test.update', [], false) }}" id="test">
	@csrf
	<div class="form-group">
	  <label for="name" class="font-weight-bold required">Tên đề thi:</label>
	  <input type="text" name="name" id="name" class="form-control" placeholder="Đề thi THPT Quốc gia..." value="{{ isset($test) ? $test->name : '' }}">
	</div>
	<div class="form-group">
	  <label for="description">Ghi chú</label>
	  <textarea name="description" id="description" class="form-control" placeholder="Đề thi thử THPT Quốc gia cho khối 12 trường THPT Chu Văn An...">{{ isset($test) ? $test->description : '' }}</textarea>
	</div>
	<div class="d-flex justify-content-between">
		<div class="form-group">
			<label for="grade_id" class="font-weight-bold">Lớp: </label>
			<select name="grade_id" id="grade">
				@foreach ($grades as $grade)
					<option value="{{ $grade->id }}" {{ isset($test) && $test->grade_id == $grade->id ? 'selected' : '' }}>{{ $grade->id }}</option>
				@endforeach
			</select>
		</div>
		<div class="form-group">
			<label for="duration" class="font-weight-bold">Thời gian: </label>
			<select name="duration" id="duration">
				@foreach ($durations as $duration)
					<option value="{{ $duration }}" {{ isset($test) && $test->duration == $duration ? 'selected' : '' }}>{{ $duration }}</option>
				@endforeach
			</select>
			<label for="duration" class="font-weight-bold"> phút</label>
		</div>
		<div class="form-group">
			<label for="quantity" class="font-weight-bold">Số câu hỏi: </label>
			<select name="no_of_questions" id="quantity">
				@foreach ($quantity as $amount)
					<option value="{{ $amount }}" {{ isset($test) && $test->no_of_questions == $amount ? 'selected' : '' }}>{{ $amount }}</option>
				@endforeach
			</select>
		</div>
	</div>

	<div class="form-group">
		<label for="questions" class="font-weight-bold">Danh sách câu hỏi:</label>
		<table class="table table-hover" id="questions">
			<thead>
				<tr>
					<th class="order-header">STT</th>
					<th class="action-header">Thao tác</th>
					<th class="content-header">Nội dung</th>
					<th class="grade-header">Lớp</th>
				</tr>
			</thead>
			<tbody>
				@if ($action == 'edit')
					@php
						$i = 0;
					@endphp
					@foreach ($test->questions as $question)
					<tr>
						<td class="order">{{ $i+1 }}</td>
						<td>
							<a class="picker text-success" href="javascript:void(0)" data-toggle="tooltip" title="Chọn câu hỏi"><i class="fas fa-crosshairs"></i></a>
							<a class="info text-primary" href="javascript:void(0)" data-toggle="tooltip" title="Xem chi tiết câu hỏi"><i class="fas fa-info-circle"></i></a>
							<a class="clear text-danger" href="javascript:void(0)" data-toggle="tooltip" title="Loại câu hỏi khỏi danh sách"><i class="fas fa-times-circle ml-1"></i></a>
						</td>
						<td>
							<input type="hidden" name="question_ids[${i}]" class="question-id" value="{{ $question->id }}">
							<div class="content scrollable">
								{!! htmlspecialchars_decode($question->content) !!}
							</div>
						</td>
						<td class="grade">
							{{ $question->grade_id }}
						</td>
					</tr>
					@php
						++$i;
					@endphp
					@endforeach	
				@endif
			</tbody>
		</table>
	</div>

	<div class="form-group">
		<button type="submit" class="btn btn-primary">
			@if ($action == 'create')
				Tạo đề thi
			@else
				Cập nhật đề thi
			@endif
		</button>
	</div>
</form>

<div class="modal fade" id="question-detail" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Chi tiết câu hỏi:</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
				<div class="question-wrapper">
					<label>Nội dung câu hỏi:</label>
					<div class="content">
					</div>
				</div>
				<div class="choices-wrapper">
					<label>Các lựa chọn:</label>
					<div class="choices">
						<ul class="list-group">
							
						</ul>
					</div>
				</div>
				<div class="solution-wrapper">
					<label>Đáp án:</label>
					<div class="solution">
					</div>
				</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="question-picker" tabindex="-1">
	<div class="modal-dialog modal-dialog-centered modal-xl">
	  <div class="modal-content">
		<div class="modal-header">
		  <h5 class="modal-title">Lựa chọn câu hỏi:</h5>
		  <button type="button" class="close" data-dismiss="modal">
			<span>&times;</span>
		  </button>
		</div>
		<div class="modal-body">
			<input type="hidden" id="selected-row" value="">
			<table class="table table-hover">
				<thead>
					<tr>
						<th class="select-header-modal">Lựa chọn</th>
						{{-- <th class="order-header-modal">STT</th> --}}
						<th class="question-header-modal">Câu hỏi</th>
						<th class="grade-header-modal">Lớp</th>
					</tr>
				</thead>
				<tbody>
					@php
					$i = 1
					@endphp
					@isset($questions)
					@foreach ($questions as $question)
					<tr class="question-row">
						<td class="text-center">
							<div class="form-check">
								<input class="form-check-input" type="radio" name="question_id" value="{{ $question->id }}">
								<label >{{ $i++ }}</label>
							</div>
						</td>
						{{-- <td class="order" >{{ $i++ }}</td> --}}
						<td class="content scrollable">{!! htmlspecialchars_decode($question->content) !!}</td>
						<td class="grade">{{ $question->grade_id }}</td>
					</tr>
					@endforeach
					@endisset
				</tbody>
			</table>
		</div>
		<div class="modal-footer">
		  <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
		  <button type="button" class="btn btn-primary" id="choose">Xác nhận</button>
		</div>
	  </div>
	</div>
</div>




@section('end')
<script>
	$(document).ready(function () {
		let quantity = $('#quantity');
		let qpicker = $('#question-picker');
		let testForm = $('#test');
		let qdetail = $('#question-detail');


		quantity.on('change', function (e) {
			let qty = parseInt($(this).val());

			let questions = '';
			for (let i = 0; i < qty; ++i) {
				questions += `<tr>
								<td class="order">${i+1}</td>
								<td>
									<a class="picker text-success" href="javascript:void(0)" data-toggle="tooltip" title="Chọn câu hỏi"><i class="fas fa-crosshairs"></i></a>
									<a class="info text-primary" href="javascript:void(0)" data-toggle="tooltip" title="Xem chi tiết câu hỏi"><i class="fas fa-info-circle"></i></a>
									<a class="clear text-danger" href="javascript:void(0)" data-toggle="tooltip" title="Loại câu hỏi khỏi danh sách"><i class="fas fa-times-circle ml-1"></i></a>
								</td>
								<td>
									<input type="hidden" name="question_ids[${i}]" class="question-id" value="">
									<div class="content scrollable">
									</div>
								</td>
								<td class="grade">
								</td>
							</tr>
							`;
			}

			$("#questions>tbody").html(questions);

			testForm.find('.picker').on('click', function (e) {
				e.preventDefault();

				let row = $(this).parent().parent();
				let rowOrder = row.find('.order').text();
				qpicker.find('#selected-row').val(rowOrder);
				qpicker.find('input[name="question_id"]:checked').prop('checked', false);

				qpicker.modal('show');
			});

			testForm.find('.clear').on('click', function (e) {
				e.preventDefault();

				let row = $(this).parent().parent();
				row.find('.content').html('');
			});

			testForm.find('.info').on('click', function (e) { 
				e.preventDefault();

				let row = $(this).parent().parent();

				let question_id = row.find('.question-id').val();
				if (question_id) {
					$.ajax({
						type: "get",
						url: "{{ route('teacher.question.get', '', false) }}"+`/${question_id}`,
						dataType: "json",
						success: function (response) {
							console.log(response);
							if (response['return_code']) {
								let jqcontent = qdetail.find('.modal-body');
								jqcontent.find('.content').html(response['question']['content']);
								let strChoices = '';
								response['question']['choices'].forEach(choice => {
									strChoices += `<li class="list-group-item">${choice['content']}</li>\n`;
								});
								jqcontent.find('.choices>ul').html(strChoices);
								jqcontent.find('.solution').html(response['question']['solution']);

								qdetail.modal('show');
							}
						}
					});
				}
				
			});
		});

		@if ($action == 'create')
			quantity.trigger('change');
		@endif


		testForm.submit(function (e) {
			e.preventDefault();

			let form_url = $(this).attr("action");
			let form_method = $(this).attr("method");
			var form_data = $(this).serialize();

			$.ajax({
				type: form_method,
				url: form_url,
				data: form_data,
				success: function (response) {
					if (response['return_code'] == '0') {
						@if ($action == 'create')
						if (!confirm("Thêm đề thành công!\nBạn có muốn tiếp tục tạo đề?")) {
							close();
						} else {
							window.location.reload();
						}
						@else
							console.log('ok');
						@endif
					} else {
						alert("Thêm đề thất bại.\nVui lòng thử lại hoặc ấn Ctrl + F5 rồi tạo lại đề.")
					}
				}
			});
		});

		qpicker.find('#choose').on('click', function (e) {
			let rowOrder = parseInt(qpicker.find('#selected-row').val());
			let selectedRadio = qpicker.find('input[name="question_id"]:checked')
			let selectedRow = selectedRadio.parent().parent().parent();
			let selectedQuestion = selectedRadio.val();

			let curRow = testForm.find(`#questions>tbody>tr:eq(${rowOrder-1})`);
			$(curRow).find('.question-id').val(selectedQuestion);
			$(curRow).find('.content').html(selectedRow.find('.content').html());
			$(curRow).find('.grade').text(selectedRow.find('.grade').text());

			qpicker.modal('hide');
		});

		qpicker.find('.question-row').on('click', function (e) {
			$(this).find('input[type="radio"]').prop('checked', true);
		});
	});

</script>
@endsection
