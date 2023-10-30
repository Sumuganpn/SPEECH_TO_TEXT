from flask import Flask, render_template, request
import speech_recognition as sr
from flask import Flask, request, render_template, redirect, url_for
import moviepy.editor as mp
import speech_recognition as sr

app = Flask(__name__)
r = sr.Recognizer()

def convert_audio_to_text(audio_file, lang):
    with sr.AudioFile(audio_file) as source:
        audio_text = r.listen(source)
        try:
            text = r.recognize_google(audio_text, language=lang)
            return text
        except:
            return "Sorry, an error occurred during audio conversion."
        

# -------------------------------------------------------------------------------------------------------------------

@app.route('/', methods=['GET', 'POST'])
def index():
    if request.method == 'POST':
        language_selection = request.form['language']
        audio_file = request.files['audio']
        if audio_file:
            audio_filename = audio_file.filename
            language = getLanguage(int(language_selection))
            converted_text = convert_audio_to_text(audio_file, language)
            return render_template('index.htm', converted_text=converted_text)
    return render_template('index.htm')

def getLanguage(argument):
    switcher = {
        1: "en-IN",
        2: "hi-IN",
        3: "kn-IN",
        4: "ta-IN"
    }
    return switcher.get(argument, "en-IN")  # Default to English

# -------------------------------------------------------------------------------------------------------------------

@app.route("/convert_video", methods=["GET", "POST"])
def video():
    text = ""  # Initialize the text variable with an empty string

    if request.method == "POST":
        # Check if a file was submitted
        if "file" not in request.files:
            return render_template("video_to_audio.htm", text=text)

        file = request.files["file"]

        # Check if the file is a video
        if file.filename == "":
            return render_template("video_to_audio.htm", text=text)

        if file:
            # Save the uploaded video
            file.save("uploaded_video.mp4")

            # Extract audio from the video
            video = mp.VideoFileClip("uploaded_video.mp4") 
            audio_file = video.audio
            audio_file.write_audiofile("geeksforgeeks.wav")

            # Initialize recognizer
            r = sr.Recognizer()

            # Load the audio file
            with sr.AudioFile("geeksforgeeks.wav") as source:
                data = r.record(source)

            # Convert speech to text
            text = r.recognize_google(data, language="ta-IN")

    return render_template("video_to_audio.htm", text=text)

if __name__ == '__main__':
    app.run(debug=True)

