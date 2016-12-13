int temp_pin = 0;
int temp_list [10] = {0,0,0,0,0,0,0,0,0,0};
int tempSum = 0;
int tempC_average;

int light_pin = 1;
int light_list [10] = {0, 0, 0, 0, 0, 0, 0, 0, 0, 0};
int light_sum = 0;
int light_average;

int ten_loops = 0;

int clock_loops = 0;
int seconds = 0;


void setup() {
  // put your setup code here, to run once:
  Serial.begin(9600);
  OCR0A = 0xAF; // 977
  TIMSK0 |= _BV(OCIE0A);
}


// interrupter every second
// Handle the "clock"
SIGNAL(TIMER0_COMPA_vect) {
  clock_loops++;
  if(clock_loops == 977) {
    seconds++;
    clock_loops = 0;
  }
}


void get_temps() {
  // Temperatures
  int tempReading = analogRead(temp_pin);
  float tempVolts = tempReading * 5.0 / 1024.0;
  float tempC = (tempVolts - 0.5) * 100.0;

  // move list items by one and add value to list
  for (int i = 9; i > 0; i--) {
        temp_list[i] = temp_list[i-1];
  }
  temp_list[0] = tempC;
}

void get_lights() {
  // Read light levels
  int lightReading = analogRead(light_pin);
  // Move values
  for (int i = 9; i > 0; i--) {
    light_list[i] = light_list[i-1];
  }
  //New value to the first list lost
  light_list[0] = lightReading;
}

void loop() {

  // collect first values every 1 seconds
  if (ten_loops < 10 && seconds >= 1) {
    get_temps();
    get_lights();
    ten_loops++;
    seconds = 0;
  }

  // when 10 first values collected, new values every 10 minutes
  else if (seconds >= 600) {
    get_temps();
    get_lights();
    
    // calculate average and send to serial
    // temp averages
    tempSum = 0;
    for (int i = 0; i < 10; i++) {
      tempSum += temp_list[i];
    }
    tempC_average = tempSum / 10;
    // End of temperatures

    // light averages
    light_sum = 0;
    for (int i = 0; i < 10; i++) {
      light_sum += light_list[i];
    }
    light_average = light_sum / 10;

    // compile message for serial and send
    String serial_message = String(tempC_average);
    serial_message += ":" + String(light_average);
    serial_message += ":";
    
    Serial.println(serial_message);
    // reset seconds
    seconds = 0;
  }
  
  
  
}
