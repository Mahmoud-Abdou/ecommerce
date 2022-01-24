<?php

namespace App\Http\Controllers;

use App\DataTables\PromoCodeDataTable;
use App\Http\Requests;
use App\Http\Requests\CreatePromoCodeRequest;
use App\Http\Requests\UpdatePromoCodeRequest;
use App\Repositories\PromoCodeRepository;
use App\Repositories\CustomFieldRepository;
use App\Repositories\UploadRepository;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Prettus\Validator\Exceptions\ValidatorException;

class PromoCodeController extends Controller
{
    /** @var  PromoCodeRepository */
    private $promoCodeRepository;

    /**
     * @var CustomFieldRepository
     */
    private $customFieldRepository;

    /**
  * @var UploadRepository
  */
    private $uploadRepository;

    private $language_id;

    public function __construct(PromoCodeRepository $promoCodeRepo, CustomFieldRepository $customFieldRepo, UploadRepository $uploadRepo)
    {
        parent::__construct();
        $this->promoCodeRepository = $promoCodeRepo;
        $this->customFieldRepository = $customFieldRepo;
        $this->uploadRepository = $uploadRepo;
        if (setting('language') =='ar') {
            $this->language_id = 2;
        } elseif (setting('language') == 'en') {
            $this->language_id = 1;
        } else {
            $this->language_id = 1;
        }
    }

    /**
     * Display a listing of the PromoCode.
     *
     * @param PromoCodeDataTable $promoCodeDataTable
     * @return Response
     */
    public function index(Request $request, PromoCodeDataTable $promoCodeDataTable)
    {
        return $promoCodeDataTable->render('promo_codes.index');
    }

    /**
     * Show the form for creating a new PromoCode.
     *
     * @return Response
     */
    public function create()
    {
        $hasCustomField = in_array($this->promoCodeRepository->model(), setting('custom_field_models', []));
        if ($hasCustomField) {
            $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->promoCodeRepository->model());
            $html = generateCustomField($customFields);
        }
        return view('promo_codes.create', [
            "customFields"=> isset($html) ? $html : false
        ]);
    }

    /**
     * Store a newly created PromoCode in storage.
     *
     * @param CreatePromoCodeRequest $request
     *
     * @return Response
     */
    public function store(CreatePromoCodeRequest $request)
    {
        $input = $request->all();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->promoCodeRepository->model());
        try {
            $promoCode = $this->promoCodeRepository->create($input);
            $promoCode->customFieldsValues()->createMany(getCustomFieldsValues($customFields, $request));

        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.saved_successfully', ['operator' => __('lang.promoCode')]));

        return redirect(route('promoCodes.index'));
    }

    /**
     * Display the specified PromoCode.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $promoCode = $this->promoCodeRepository->findWithoutFail($id);

        if (empty($promoCode)) {
            Flash::error('PromoCode not found');

            return redirect(route('promoCodes.index'));
        }

        return view('promo_codes.show')->with('promoCode', $promoCode);
    }

    /**
     * Show the form for editing the specified PromoCode.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        
        $promoCode = $this->promoCodeRepository->findWithoutFail($id);
        

        if (empty($promoCode)) {
            Flash::error(__('lang.not_found', ['operator' => __('lang.promoCode')]));

            return redirect(route('promoCodes.index'));
        }
        $customFieldsValues = $promoCode->customFieldsValues()->with('customField')->get();
        $customFields =  $this->customFieldRepository->findByField('custom_field_model', $this->promoCodeRepository->model());
        $hasCustomField = in_array($this->promoCodeRepository->model(), setting('custom_field_models', []));
        if ($hasCustomField) {
            $html = generateCustomField($customFields, $customFieldsValues);
        }

        return view('promo_codes.edit')
        ->with('promoCode', $promoCode)
        ->with("customFields", isset($html) ? $html : false);
    }

    /**
     * Update the specified PromoCode in storage.
     *
     * @param  int              $id
     * @param UpdatePromoCodeRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePromoCodeRequest $request)
    {
        $promoCode = $this->promoCodeRepository->findWithoutFail($id);
        
        
        if (empty($promoCode)) {
            Flash::error('PromoCode not found');
            return redirect(route('promoCodes.index'));
        }
        $input = $request->all();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->promoCodeRepository->model());
        try {
            $promoCode = $this->promoCodeRepository->update($input, $id);

            foreach (getCustomFieldsValues($customFields, $request) as $value) {
                $PromoCode->customFieldsValues()
                    ->updateOrCreate(['custom_field_id'=>$value['custom_field_id']], $value);
            }
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.updated_successfully', ['operator' => __('lang.promoCode')]));

        return redirect(route('promoCodes.index'));
    }

    /**
     * Remove the specified PromoCode from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $promoCode = $this->promoCodeRepository->findWithoutFail($id);
        
        if (empty($promoCode)) {
            Flash::error('PromoCode not found');

            return redirect(route('promoCodes.index'));
        }

        $this->promoCodeRepository->delete($id);

        Flash::success(__('lang.deleted_successfully', ['operator' => __('lang.promoCode')]));

        return redirect(route('promoCodes.index'));
    }

    /**
     * Remove Media of PromoCode
     * @param Request $request
     */
    public function removeMedia(Request $request)
    {
        $input = $request->all();
        $PromoCode = $this->promoCodeRepository->findWithoutFail($input['id']);
        try {
            if ($PromoCode->hasMedia($input['collection'])) {
                $PromoCode->getFirstMedia($input['collection'])->delete();
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
