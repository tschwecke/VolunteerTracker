<?php


class SerializationMgr {

	public function serialize($object) {
//		foreach($object as $key => $value) {
//			if($value == null) {
//				unset($object[key]);
//			}
//		}

		$serializedObject = json_encode($object);

		return $serializedObject;
	}

	public function deserialize($serializedObject, $objectType) {
		$propertyArray = json_decode($serializedObject);
		if(is_array($propertyArray)) {
			$object = Array();
			for($i=0; $i < count($propertyArray); $i++) {
				array_push($object, new DomainObject($objectType, $propertyArray[$i]));
			}
		}
		else {
			$object = new DomainObject($objectType, $propertyArray);
		}

		return $object;
	}
}
