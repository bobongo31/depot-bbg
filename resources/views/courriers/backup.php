<div class="scroll-animated mb-3">
                        <label for="annexes" class="form-label">
                            <i class="fa-solid fa-file-arrow-up"></i> Annexes (JPG, PDF, DOCX, etc.)
                        </label>
                        <input type="file" class="form-control" id="annexes" name="annexes[]" multiple>

                        <div class="mt-2">
                            <div class="progress d-none" id="chunkProgress">
                                <div class="progress-bar" role="progressbar" style="width:0%" id="chunkBar">0%</div>
                            </div>
                            <div id="chunkStatus" class="small mt-1"></div>
                        </div>
                        {{-- Préremplir uploaded_paths avec le JSON des annexes du draft s'il existe --}}
                        <input type="hidden" name="uploaded_paths" id="uploaded_paths" value='{{ old('uploaded_paths', isset($draft) && !empty($draft->annexes) ? json_encode($draft->annexes->pluck("file_path")->toArray()) : "") }}'>
                        {{-- Préremplir draft_id pour que le JS sache à quel draft se rattacher --}}
                        <input type="hidden" name="draft_id" id="draft_id" value="{{ $draft->id ?? '' }}">
                    </div>