<div class="ss_featured_recruitment">
									<?php 
									$query_interested_job=DB::table('recruitment')
									->join('employer','recruitment.employer_id','=','employer.id')
									->join('salary','recruitment.salary_id','=','salary.id');
									$query_interested_job->where('recruitment.status',1);
									$query_interested_job->where('recruitment.status_employer',1);
									$query_interested_job->where('recruitment.status_interested',1);
									$source_interested_job=$query_interested_job->select(
										'recruitment.id',
										'recruitment.fullname',
										'recruitment.alias',
										'recruitment.duration',
										'recruitment.status_hot',
										'salary.fullname as salary_name',
										'employer.fullname as employer_fullname',
										'employer.alias as employer_alias',
										'employer.logo'
									)
									->groupBy(
										'recruitment.id',
										'recruitment.fullname',
										'recruitment.alias',
										'recruitment.duration',
										'recruitment.status_hot',
										'salary.fullname',
										'employer.fullname',
										'employer.alias',
										'employer.logo'
									)
									->orderBy('recruitment.id', 'desc')
									->take(50)
									->get()
									->toArray();
									if(count(@$source_interested_job) > 0){										
										?>										
										<div class="owl-carousel interested-recruitment owl-theme">
											<?php 
											$k=0;
											
											$data_interested_job=convertToArray(@$source_interested_job);
											foreach ($data_interested_job as $key => $value) {
												$hot_interested_fullname=truncateString(@$value['fullname'],50) ;
												$hot_interested_employer=truncateString(@$value['employer_fullname'],9999);
												$hot_interested_duration=datetimeConverterVn(@$value['duration']);
												$hot_interested_hot_gif='';
												if((int)@$value['status_hot'] == 1){
													$hot_interested_hot_gif= '&nbsp;<img src="'.asset('upload/hot.gif').'" width="40" />';
												}
												$hot_interested_logo='';
												if(!empty(@$value['logo'])){
													$hot_interested_logo=asset('upload/'.$width.'x'.$height.'-'.@$value['logo']);
												}else{
													$hot_interested_logo=asset('upload/no-logo.png');
												}
												$source_province=DB::table('province')
												->join('recruitment_place','province.id','=','recruitment_place.province_id')							
												->where('recruitment_place.recruitment_id',(int)@$value['id'])
												->select(								
													'province.fullname',
													'province.alias'								
												)
												->groupBy(								
													'province.fullname',
													'province.alias'								
												)
												->orderBy('province.id', 'desc')						
												->get()
												->toArray();	
												$data_province=convertToArray(@$source_province);					
												$province_text='';
												foreach ($data_province as $key_province => $value_province) {
													$province_text.='<span class="margin-left-15"><a title="'.@$value_province['fullname'].'" href="'.route('frontend.index.index',[@$value_province['alias']]).'">'.@$value_province['fullname'].'</a></span>';
												}
												if($k%5 == 0){
													
													echo '<div class="item" >';
												}
												?>
												<div class="jp_job_post_main_wrapper_cont jp_job_post_main_wrapper_cont2">
													<div class="jp_job_post_main_wrapper">
														<div class="row">
															<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
																<div class="jp_job_post_side_img">
																	<a title="<?php echo @$value['employer_fullname']; ?>" href="<?php echo route('frontend.index.index',[@$value['employer_alias']]); ?>"><img src="<?php echo @$hot_interested_logo; ?>" alt="<?php echo @$value['employer_fullname']; ?>" title="<?php echo @$value['employer_fullname']; ?>" /></a>
																</div>
																<div class="jp_job_post_right_cont">
																	<h4 class="recent-job-title"><a title="<?php echo @$value['fullname']; ?>" href="<?php echo route('frontend.index.index',[@$value['alias']]); ?>"><?php echo @$hot_interested_fullname; ?></a></h4>
																	<p class="recent-job-employer-name"><a title="<?php echo @$value['employer_fullname']; ?>" href="<?php echo route('frontend.index.index',[@$value['employer_alias']]); ?>"><?php echo @$hot_interested_employer; ?></a></p> 
																	<ul>
																		<li><i class="fa fa-cc-paypal"></i><span class="margin-left-15"><?php echo @$value['salary_name']; ?></span></li>
																		<li><i class="fa fa-map-marker"></i>&nbsp; <?php echo @$province_text; ?></li>
																	</ul>
																</div>
															</div>
															<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
																<div class="jp_job_post_right_btn_wrapper">
																	<ul>																		
																		<li><a href="javascript:void(0);">Ứng tuyển</a></li>
																	</ul>
																</div>
															</div>
														</div>
													</div>													
												</div>	
												<?php
												$k++;
												if($k%5==0 || $k == count(@$data_interested_job)){
													echo '</div>';
												} 	
											}		
											?>
										</div>
										<?php										 
									}		
									?>																										
								</div>				