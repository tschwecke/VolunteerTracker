<?php

class DomainObject {
	private $objectType = '';

	public function __construct($objectType, $properties) {
		$this->objectType = $objectType;

		foreach($properties as $k => $value) {
			if($k == ($objectType . '_PK')) {
				$propertyName = 'id';
			}
			else {
				$propertyName = str_replace('_PK', 'Id', $k);
				$propertyName = lcfirst($propertyName);
			}
			
			$this->{$propertyName} = $value;
		}
	}

}

?>
