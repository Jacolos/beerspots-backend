import requests
import json
from datetime import datetime
import time
import sys
import os
from typing import Dict, List, Tuple, Set

# Add API configuration
API_BASE_URL = "https://piwo.jacolos.pl/api"  # Replace with your actual API base URL
API_TOKEN = "59|B4lOxTgGtYr9bjWiIfXKWeYMQsaaRjvuXnHfcWcSd8d7c4af"  # Replace with your actual bearer token

HEADERS = {
    "Authorization": f"Bearer {API_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json"
}

def print_progress(message):
    """Print progress message with timestamp"""
    timestamp = datetime.now().strftime('%H:%M:%S')
    print(f"[{timestamp}] {message}")
    sys.stdout.flush()

def normalize_time(time_str):
    """Normalize time format to HH:MM"""
    if not time_str:
        return None
    time_str = time_str.strip()
    if time_str == "24/7":
        return "00:00"
    if len(time_str) == 4 and time_str[1] == ":":
        time_str = "0" + time_str
    return time_str

def parse_complex_hours(opening_hours_str):
    """Parse complex opening hours string into structured format"""
    if not opening_hours_str:
        return {}
        
    days_map = {
        'Mo': 'monday',
        'Tu': 'tuesday',
        'We': 'wednesday',
        'Th': 'thursday',
        'Fr': 'friday',
        'Sa': 'saturday',
        'Su': 'sunday'
    }
    
    result = {}
    
    if opening_hours_str == "24/7":
        for day in days_map.values():
            result[day] = {
                "open": "00:00",
                "close": "23:59",
                "closed": False
            }
        return result
    
    try:
        rules = opening_hours_str.split(';')
        
        for rule in rules:
            rule = rule.strip()
            if not rule:
                continue
                
            if 'off' in rule.lower() or 'closed' in rule.lower():
                days_part = rule.split(' ')[0]
                current_days = resolve_days_range(days_part, days_map)
                for day in current_days:
                    result[day] = {"closed": True}
                continue
            
            parts = rule.split(' ')
            if len(parts) >= 2:
                days_part = parts[0]
                times_part = ' '.join(parts[1:])
                
                current_days = resolve_days_range(days_part, days_map)
                
                times = times_part.split('-')
                if len(times) == 2:
                    open_time = normalize_time(times[0])
                    close_time = normalize_time(times[1])
                    
                    if open_time and close_time:
                        for day in current_days:
                            result[day] = {
                                "open": open_time,
                                "close": close_time,
                                "closed": False
                            }
    except Exception as e:
        print_progress(f"Error parsing hours: {str(e)} for input: {opening_hours_str}")
        
    for day in days_map.values():
        if day not in result:
            result[day] = {"closed": True}
            
    return result

def resolve_days_range(days_part, days_map):
    """Resolve day ranges like Mo-Fr into list of days"""
    result = []
    day_specs = days_part.split(',')
    
    for spec in day_specs:
        if '-' in spec:
            start_day, end_day = spec.split('-')
            if start_day in days_map and end_day in days_map:
                start_idx = list(days_map.keys()).index(start_day)
                end_idx = list(days_map.keys()).index(end_day)
                days = list(days_map.keys())[start_idx:end_idx + 1]
                result.extend([days_map[d] for d in days])
        else:
            if spec in days_map:
                result.append(days_map[spec])
                
    return result

def build_full_address(tags):
    """Build complete address from OSM tags"""
    address_parts = []
    
    street = tags.get("addr:street", "").strip()
    house_number = tags.get("addr:housenumber", "").strip()
    
    if street and house_number:
        address_parts.append(f"{street} {house_number}")
    elif street:
        address_parts.append(street)
    
    city = tags.get("addr:city", "").strip()
    
    if address_parts:
        full_address = ", ".join(address_parts)
        if city:
            full_address += f", {city}"
        return full_address
    return city or "Polska"

def get_description(tags):
    """Build rich description from available tags"""
    parts = []
    
    if tags.get("cuisine"):
        cuisines = tags["cuisine"].split(";")
        parts.append(f"Kuchnia: {', '.join(cuisines)}")
    
    if tags.get("website"):
        parts.append(f"Website: {tags['website']}")
        
    if tags.get("phone"):
        parts.append(f"Tel: {tags['phone']}")
        
    features = []
    if tags.get("outdoor_seating") == "yes":
        features.append("ogródek")
    if tags.get("wheelchair") == "yes":
        features.append("dostępne dla niepełnosprawnych")
    if tags.get("delivery") == "yes":
        features.append("dowóz")
    if features:
        parts.append("Udogodnienia: " + ", ".join(features))
        
    if not parts:
        return f"Lokal typu: {tags.get('amenity', 'bar')}"
        
    return " | ".join(parts)

def check_venue_exists(venue_name: str) -> Tuple[bool, Dict]:
    """Check if venue exists and return its details"""
    response = requests.get(
        f"{API_BASE_URL}/beer-spots",
        headers=HEADERS,
        params={'search': venue_name}
    )
    
    if response.status_code == 200:
        venues_data = response.json()
        if 'data' in venues_data and 'data' in venues_data['data']:
            venues = venues_data['data']['data']
            for venue in venues:
                if venue.get('name') == venue_name:
                    return True, venue
    return False, {}

def update_beer_spot_status(venue_name: str, status: str = 'inactive') -> bool:
    """Update status of a beer spot"""
    exists, venue = check_venue_exists(venue_name)
    if exists and venue.get('id'):
        update_response = requests.put(
            f"{API_BASE_URL}/beer-spots/{venue['id']}",
            headers=HEADERS,
            json={
                'status': status,
                'verified': False
            }
        )
        return update_response.status_code == 200
    return False

def check_venue_exists(venue_name: str, latitude: float = None, longitude: float = None) -> Tuple[bool, Dict]:
    """Check if venue exists by name and coordinates"""
    response = requests.get(
        f"{API_BASE_URL}/beer-spots",
        headers=HEADERS
    )
    
    if response.status_code == 200:
        venues_data = response.json()
        if 'data' in venues_data and 'data' in venues_data['data']:
            venues = venues_data['data']['data']
            for venue in venues:
                # Sprawdź czy nazwa się zgadza
                if venue.get('name') != venue_name:
                    continue
                    
                # Jeśli podano koordynaty, sprawdź czy są zbliżone
                if latitude is not None and longitude is not None:
                    venue_lat = float(venue.get('latitude', 0))
                    venue_lon = float(venue.get('longitude', 0))
                    
                    # Sprawdź czy koordynaty są w promieniu ~100m
                    if (abs(venue_lat - latitude) < 0.001 and 
                        abs(venue_lon - longitude) < 0.001):
                        return True, venue
                else:
                    return True, venue
    return False, {}

def get_address_from_coords(latitude: float, longitude: float) -> str:
    """Get address from coordinates using Nominatim"""
    try:
        # Add delay to respect Nominatim's usage policy
        time.sleep(1)
        
        headers = {
            'User-Agent': 'BeerSpotApp/1.0'
        }
        response = requests.get(
            f"https://nominatim.openstreetmap.org/reverse?lat={latitude}&lon={longitude}&format=json&accept-language=pl",
            headers=headers
        )
        
        if response.status_code == 200:
            data = response.json()
            address = data.get('address', {})
            
            # Get street name or road
            street = address.get('road')
            
            # Get house number if available
            house_number = address.get('house_number', '')
            
            # Get city name (try different fields)
            city = (address.get('city') or address.get('town') or 
                   address.get('village') or address.get('municipality'))
            
            # Build simplified address
            if street and city:
                if house_number:
                    return f"{street} {house_number}, {city}"
                return f"{street}, {city}"
            elif city:
                return city
            
    except Exception as e:
        print_progress(f"Error getting address from coordinates: {str(e)}")
    
    return "Polska"

def build_full_address(tags, latitude: float = None, longitude: float = None):
    """Build complete address from OSM tags or coordinates"""
    address_parts = []
    
    street = tags.get("addr:street", "").strip()
    house_number = tags.get("addr:housenumber", "").strip()
    
    if street and house_number:
        address_parts.append(f"{street} {house_number}")
    elif street:
        address_parts.append(street)
    
    city = tags.get("addr:city", "").strip()
    
    if address_parts:
        full_address = ", ".join(address_parts)
        if city:
            full_address += f", {city}"
        return full_address
    
    # If no address in tags and coordinates are provided, try to get address from coordinates
    if not address_parts and latitude is not None and longitude is not None:
        return get_address_from_coords(latitude, longitude)
        
    return city or "Polska"

def add_new_venue(venue: Dict) -> bool:
    """Add new venue if it doesn't exist"""
    try:
        tags = venue['tags']
        venue_name = tags['name']
        venue_lat = float(venue.get('lat', 0))
        venue_lon = float(venue.get('lon', 0))
        
        # Check if venue already exists by name AND location
        exists, existing_venue = check_venue_exists(venue_name, venue_lat, venue_lon)
        if exists:
            print_progress(f"Venue already exists: {venue_name} at {venue_lat}, {venue_lon}")
            return False
        
        # Get address from coordinates if needed
        address = build_full_address(tags, venue_lat, venue_lon)
        
        # Format venue data
        venue_data = {
            'name': venue_name,
            'address': address,
            'latitude': str(venue_lat),
            'longitude': str(venue_lon),
            'description': get_description(tags),
            'opening_hours': parse_complex_hours(tags.get('opening_hours', '')),
            'status': 'pending',
            'verified': False
        }
        
        # Create new venue
        response = requests.post(
            f"{API_BASE_URL}/beer-spots",
            headers=HEADERS,
            json=venue_data
        )
        
        if response.status_code == 201:
            print_progress(f"Successfully added new venue: {venue_name} with address: {address}")
            return True
        else:
            print_progress(f"Failed to add new venue: {venue_name}, Status: {response.status_code}")
            return False
            
    except Exception as e:
        print_progress(f"Error adding venue: {str(e)}")
        return False

def load_previous_data() -> Dict:
    """Load previous data from JSON file"""
    try:
        if os.path.exists("poland_raw_data.json"):
            with open("poland_raw_data.json", "r", encoding="utf-8") as f:
                return json.load(f)
    except Exception as e:
        print_progress(f"Warning: Could not load previous data: {e}")
    return {"elements": []}

def compare_data(previous_data: Dict, current_data: Dict) -> Tuple[List[Dict], List[Dict]]:
    """Compare previous and current data to find changes"""
    def create_venue_key(element: Dict) -> str:
        tags = element.get("tags", {})
        key_parts = []
        name = tags.get("name", "").strip().lower()
        key_parts.append(name)
        lat = element.get("lat", 0)
        lon = element.get("lon", 0)
        key_parts.append(f"{lat:.5f}")
        key_parts.append(f"{lon:.5f}")
        return "__".join(key_parts)
    
    previous_venues = {create_venue_key(elem): elem 
                      for elem in previous_data.get("elements", [])
                      if "tags" in elem and "name" in elem.get("tags", {})}
    
    current_venues = {create_venue_key(elem): elem 
                     for elem in current_data.get("elements", [])
                     if "tags" in elem and "name" in elem.get("tags", {})}
    
    added_keys = set(current_venues.keys()) - set(previous_venues.keys())
    removed_keys = set(previous_venues.keys()) - set(current_venues.keys())
    
    added_venues = [current_venues[key] for key in added_keys]
    removed_venues = [previous_venues[key] for key in removed_keys]
    
    return added_venues, removed_venues

def main():
    query = """
    [out:json][timeout:180];
    area["name"="Polska"]["admin_level"=2]->.poland;
    (
      node["amenity"~"^(pub|bar|restaurant)$"]["name"](area.poland);
      way["amenity"~"^(pub|bar|restaurant)$"]["name"](area.poland);
    );
    out body;
    >;
    out skel qt;
    """

    try:
        print_progress("Starting data collection for entire Poland...")
        
        # Load previous data and fetch current data
        previous_data = load_previous_data()
        response = requests.post(
            "https://overpass-api.de/api/interpreter",
            data={"data": query},
            timeout=180
        )
        response.raise_for_status()
        
        current_data = response.json()
        print_progress(f"Found {len(current_data.get('elements', []))} venues across Poland")
        
        # Process changes if we have previous data
        if previous_data.get("elements"):
            added_venues, removed_venues = compare_data(previous_data, current_data)
            
            # Handle removed venues first
            if removed_venues:
                print_progress(f"Processing {len(removed_venues)} removed venues...")
                for venue in removed_venues:
                    if 'tags' in venue and 'name' in venue['tags']:
                        venue_name = venue['tags']['name']
                        if update_beer_spot_status(venue_name):
                            print_progress(f"Successfully marked venue as inactive: {venue_name}")
                        else:
                            print_progress(f"Failed to update venue status: {venue_name}")
            
            # Then handle new venues
            if added_venues:
                print_progress(f"Processing {len(added_venues)} new venues...")
                for venue in added_venues:
                    if 'tags' in venue and 'name' in venue['tags']:
                        add_new_venue(venue)
        
        # Save current data for future comparison
        with open("poland_raw_data.json", "w", encoding="utf-8") as f:
            json.dump(current_data, f, ensure_ascii=False, indent=2)
        
        print_progress("Data synchronization completed successfully")

    except requests.Timeout:
        print_progress("Error: Request timed out")
    except requests.RequestException as e:
        print_progress(f"Error communicating with Overpass API: {e}")
    except Exception as e:
        print_progress(f"Unexpected error: {e}")
        raise

if __name__ == "__main__":
    main()