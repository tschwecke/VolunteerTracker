<?php
require_once 'Util/Dal.php';
require_once 'Models/DomainObject.php';

class ProfileSvc {

	public function getByVolunteerId($volunteerId) {
		$results = Dal::executeQuery("Profile_Select_ByVolunteer_PK", $volunteerId);

		if(count($results) == 0) {
			$profile = null;
		}
		else {
			$profile = new DomainObject('Profile', $results[0]);
		}

		$profile->preferEmail = (bool)$profile->preferEmail;
		$profile->preferPhone = (bool)$profile->preferPhone;

		return $profile;
	}

	public function getByFamilyId($familyId) {
		$results = Dal::executeQuery("Profile_Select_ByFamilyId", $familyId);
		if(count($results) == 0) {
			$profile = null;
		}
		else {
			$profile = new DomainObject('Profile', $results[0]);
		}

		return $profile;
	}

	public function getAll($familyId) {
		$results = Dal::executeQuery("Profile_Select_ByFamilyId", $familyId);
		$profiles = array();
		for($i=0; $i<count($results); $i++) {
			$profile = new DomainObject('Profile', $results[0]);
			array_push($profiles, $profile);
		}

		return $profiles;
        }

	public function save($profile) {
		if($profile->id > 0) {
			Dal::execute("Profile_Update_ByPK", $profile->id,
								$profile->familyId,
								$profile->streetAddress,
								$profile->city,
								$profile->state,
								$profile->zipCode,
								$profile->primaryPhoneNbr,
								$profile->primaryPhoneType,
								$profile->bestTimePrimary,
								$profile->secondaryPhoneNbr,
								$profile->secondaryPhoneType,
								$profile->bestTimeSecondary,
								$profile->preferEmail,
								$profile->preferPhone,
								$profile->relationshipToOrganization);
		}
		else {
			$results = Dal::executeQuery("Profile_Insert", $profile->volunteerId,
									$profile->familyId,
									$profile->streetAddress,
									$profile->city,
									$profile->state,
									$profile->zipCode,
									$profile->primaryPhoneNbr,
									$profile->primaryPhoneType,
									$profile->bestTimePrimary,
									$profile->secondaryPhoneNbr,
									$profile->secondaryPhoneType,
									$profile->bestTimeSecondary,
									$profile->preferEmail,
									$profile->preferPhone,
									$profile->relationshipToOrganization);

			$profile->id = $results[0]['NewId'];
		}
        }
}

/*
using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using Ava.Volunteer.Services.Util;
using System.Data.Common;
using Ava.Volunteer.Services.Models;
using System.Data.Odbc;

namespace Ava.Volunteer.Services.Domain
{
    public class ProfileSvc
    {

        public virtual Profile GetByVolunteerId(int volunteerId)
        {
            Profile profile = new Profile();
            Dal dal = new Dal();

            DbDataReader reader = dal.ExecuteQuery("Profile_Select_ByVolunteer_PK", new OdbcParameter("volunteer_PK", volunteerId));

            if (reader.HasRows)
            {
                profile = new Profile();

                profile.Id = reader.GetInt32(0);
                profile.VolunteerId = reader.GetInt32(1);
                profile.FamilyId = reader.GetInt32(2);
                profile.StreetAddress = reader.GetString(3);
                profile.City = reader.GetString(4);
                profile.State = reader.GetString(5);
                profile.ZipCode = reader.GetString(6);
                profile.PrimaryPhoneNbr = reader.GetString(7);
                profile.PrimaryPhoneType = reader.GetString(8);
                profile.BestTimePrimary = reader.GetString(9);
                profile.SecondaryPhoneNbr = reader.GetString(10);
                profile.SecondaryPhoneType = reader.GetString(11);
                profile.BestTimeSecondary = reader.GetString(12);
                profile.PreferEmail = Convert.ToBoolean(reader.GetInt32(13));
                profile.PreferPhone = Convert.ToBoolean(reader.GetInt32(14));
                profile.RelationshipToOrganization = reader.GetString(15);
            }

            return profile;
        }

        public virtual IList<Profile> GetByFamilyId(int familyId)
        {
            IList<Profile> profiles = new List<Profile>();
            Dal dal = new Dal();

            DbDataReader reader = dal.ExecuteQuery("Profile_Select_ByFamilyId", new OdbcParameter("familyId", familyId));

            while (reader.Read())
            {
                Profile profile = new Profile();

                profile.Id = reader.GetInt32(0);
                profile.VolunteerId = reader.GetInt32(1);
                profile.FamilyId = reader.GetInt32(2);
                profile.StreetAddress = reader.GetString(3);
                profile.City = reader.GetString(4);
                profile.State = reader.GetString(5);
                profile.ZipCode = reader.GetString(6);
                profile.PrimaryPhoneNbr = reader.GetString(7);
                profile.PrimaryPhoneType = reader.GetString(8);
                profile.BestTimePrimary = reader.GetString(9);
                profile.SecondaryPhoneNbr = reader.GetString(10);
                profile.SecondaryPhoneType = reader.GetString(11);
                profile.BestTimeSecondary = reader.GetString(12);
                profile.PreferEmail = Convert.ToBoolean(reader.GetInt32(13));
                profile.PreferPhone = Convert.ToBoolean(reader.GetInt32(14));
                profile.RelationshipToOrganization = reader.GetString(15);

                profiles.Add(profile);
            }

            return profiles;
        }

        public virtual void Save(Profile profile)
        {
            Dal dal = new Dal();

            if (profile.Id > 0)
            {
                //Update
                dal.Execute("Profile_Update_ByPK", new OdbcParameter("profile_PK", profile.Id),
                                                    new OdbcParameter("familyId", profile.FamilyId),
                                                    new OdbcParameter("streetAddress", profile.StreetAddress),
                                                    new OdbcParameter("city", profile.City),
                                                    new OdbcParameter("state", profile.State),
                                                    new OdbcParameter("zipCode", profile.ZipCode),
                                                    new OdbcParameter("primaryPhoneNbr", profile.PrimaryPhoneNbr),
                                                    new OdbcParameter("primaryPhoneType", profile.PrimaryPhoneType),
                                                    new OdbcParameter("bestTimePrimary", profile.BestTimePrimary),
                                                    new OdbcParameter("secondaryPhoneNbr", profile.SecondaryPhoneNbr),
                                                    new OdbcParameter("secondaryPhoneType", profile.SecondaryPhoneType),
                                                    new OdbcParameter("bestTimeSecondary", profile.BestTimeSecondary),
                                                    new OdbcParameter("preferEmail", profile.PreferEmail),
                                                    new OdbcParameter("preferPhone", profile.PreferPhone),
                                                    new OdbcParameter("relationshipToOrganization", profile.RelationshipToOrganization));
            }
            else
            {
                //Insert
                int id = dal.Execute("Profile_Insert", new OdbcParameter("volunteer_PK", profile.VolunteerId),
                                                        new OdbcParameter("familyId", profile.FamilyId),
                                                        new OdbcParameter("streetAddress", profile.StreetAddress),
                                                        new OdbcParameter("city", profile.City),
                                                        new OdbcParameter("state", profile.State),
                                                        new OdbcParameter("zipCode", profile.ZipCode),
                                                        new OdbcParameter("primaryPhoneNbr", profile.PrimaryPhoneNbr),
                                                        new OdbcParameter("primaryPhoneType", profile.PrimaryPhoneType),
                                                        new OdbcParameter("bestTimePrimary", profile.BestTimePrimary),
                                                        new OdbcParameter("secondaryPhoneNbr", profile.SecondaryPhoneNbr),
                                                        new OdbcParameter("secondaryPhoneType", profile.SecondaryPhoneType),
                                                        new OdbcParameter("bestTimeSecondary", profile.BestTimeSecondary),
                                                        new OdbcParameter("preferEmail", profile.PreferEmail),
                                                        new OdbcParameter("preferPhone", profile.PreferPhone),
                                                        new OdbcParameter("relationshipToOrganization", profile.RelationshipToOrganization));
                profile.Id = id;
            }
        }

    }
}
*/
