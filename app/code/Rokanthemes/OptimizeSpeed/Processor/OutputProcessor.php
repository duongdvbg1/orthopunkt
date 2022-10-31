<?php
namespace Rokanthemes\OptimizeSpeed\Processor;

use Magento\Framework\App\Request\Http as Request;
use Magento\Framework\App\View\Deployment\Version\StorageInterface;
use Rokanthemes\OptimizeSpeed\Api\Processor\OutputProcessorInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;

class OutputProcessor implements OutputProcessorInterface
{

    /**
     * @var Request
     */
    private $request;

    /**
     * @var string
     */
    private $deploymentVersion;
	
	/**
     * @var string
     */
    private $_urlInterface;
	

    public function __construct(
        ScopeConfigInterface $scopeConfig,
        Request $request,
		\Magento\Framework\UrlInterface $urlInterface,    
        StorageInterface $storage
    )
    {
		$this->scopeConfig = $scopeConfig;
        $this->request           = $request;
        $this->deploymentVersion = $storage->load();
		$this->_urlInterface = $urlInterface;
    }

    /**
     * {@inheritdoc}
     */
    public function process($content)
    {
        if ($this->request->isAjax() || strpos($content, '{"') === 0) {
            return $content;
        }
		$fonts  = [];
        $config = $this->scopeConfig->getValue(
            'optimizespeed/optimizespeed_html/preload',
            ScopeInterface::SCOPE_STORE
        );
		if (is_array($config)) {
        $config = json_decode($config, true);
            foreach ($config as $item) {
                $item    = (array)$item;
                $fonts[] = $item['expression'];
            }
        }
        $preloadFonts = $fonts;
        $preload = '';
        foreach ($preloadFonts as $font) {
            $font = preg_replace('/version\d{10}/', 'version' . $this->deploymentVersion, $font);
			if(strpos($font, 'fonts.gstatic.com') !== false){
				$font = $font;
			}else{
				if(strpos($font, '/') !== 0 ) {
					$font = '/' . $font;
				}
			}
            $preload .= '<link rel="preload" href="' . $font . '" as="font" crossorigin="anonymous"/>';
        }
        $content = preg_replace('/<\/\s*title\s*>/is', '</title>' . $preload, $content);
		$content = preg_replace('#type="text/javascript"#', '', $content);
		
        if (preg_match_all('/<div([^>]*?)data-background-images=(\"|\'|)(.*?)(\"|\'|)(.*?)>/is', $content, $data_images)) {
			foreach ($data_images[0] as $key => $image) {
				$attributes = $data_images[1][$key];
				if (preg_match_all('/class="([^>]*?)(.*?)"/is', $attributes, $data_class)) {
					if(isset($data_class[2])){
						if(isset($data_class[2][0]) && $data_class[2][0]){
							$class = explode(" ",$data_class[2][0]);
							foreach ($class as $item) {
								if($item){
									if(preg_match_all('#<style type="text/css">(.*?)</style>#is', $content, $data_style)) {
										if(isset($data_style[0]) && isset($data_style[1])){
											foreach($data_style[0] as $key_style => $style){
												if(isset($data_style[1][$key_style])){
													$attributes_style = $data_style[1][$key_style];
													if($attributes_style){
														if(strpos($attributes_style, $item) !== false){
															$data_bgset = '';
															if (preg_match_all("/data-background-images='([^>]*?)(.*?)'/is", $data_images[0][$key], $images)){
																if(isset($images[2][0])){
																	$bgset_images = str_replace('\\','',$images[2][0]);
																	$bgset_images = json_decode($bgset_images,true);
																	if($bgset_images && count($bgset_images) > 0){
																		if(isset($bgset_images['desktop_image'])){
																			$data_bgset = $bgset_images['desktop_image'];
																			if(isset($bgset_images['mobile_image'])){
																				$data_bgset = $bgset_images['mobile_image']. '[(max-width: 768px)] | '.$data_bgset;
																			} 
																		}else{
																			if(isset($bgset_images['mobile_image'])){
																				$data_bgset = $bgset_images['mobile_image']. '[(max-width: 768px)]';
																			}
																		}
																	}
																}
															}
															if($data_bgset){
																$data_bgset = 'data-bgset="'.$data_bgset.'"';
																$content = str_replace($style,'', $content);
																$new_content = $data_class[2][0].' lazyload" '.$data_bgset;
																$content = str_replace($data_class[2][0].'"',$new_content, $content);
															}
														}
													}
												}
											}
										}
									}
								}
							}
						}
					}
				}
			}
		}
		
		$content = preg_replace('#<style type="text/css">#', '<style>', $content);
		
		if(preg_match_all('#<style>(.*?)</style>#is', $content, $inline_styles)) {
			if(isset($inline_styles[0])){
				foreach($inline_styles[0] as $key_inline_styles => $inline_style){
					if(strpos($inline_style, '#html-body') !== false || strpos($inline_style, 'rs-') !== false){
						$content = str_replace($inline_style,'', $content);
						$content = str_replace('</head>',$inline_style.'</head>', $content);
					}
				}
			}
		}
        return $content;
    }
}
