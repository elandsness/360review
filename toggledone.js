//
//    Copyright 2010-2014 Erik Landsness
//    This file is part of 360 Feedback.
//
//    360 Feedback is free software: you can redistribute it and/or modify
//    it under the terms of the GNU General Public License as published by
//    the Free Software Foundation, either version 3 of the License, or any later version.
//
//    360 Feedback is distributed in the hope that it will be useful,
//    but WITHOUT ANY WARRANTY; without even the implied warranty of
//    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//    GNU General Public License for more details.
//
//    You should have received a copy of the GNU General Public License
//    along with 360 Feedback.  If not, see <http://www.gnu.org/licenses/>.
//

function toggle (){
    var current = feedback.submit.value;
    switch (current){
        case 'Save':
            feedback.submit.value = 'Complete';
        break;
        case 'Complete':
            feedback.submit.value = 'Save';
        break;
    }
}

